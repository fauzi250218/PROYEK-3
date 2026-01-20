<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Murid;
use App\Models\Perkembangan;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GuruDashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru ?? null;
        if (!$guru) abort(403, 'Akses ditolak: Guru tidak ditemukan.');

        $namaGuru = $guru->user->name;
        $tanggalHariIni = Carbon::today()->format('Y-m-d');

        // NORMALISASI NAMA (biar Pak Andi == Andi Setiawan tetap match)
        $normalize = function($s) {
            $s = preg_replace('/\b(Pak|Bpk|Bu|Ibu|Mr|Mrs|Ms)\b/iu', '', $s);
            $s = preg_replace('/[^\p{L}\p{N}]/u', '', $s);
            return Str::lower($s);
        };

        $normGuru = $normalize($namaGuru);

        // ===== JADWAL HARI INI =====
        $jadwalHariIni = Jadwal::with('kelas')
            ->whereDate('tanggal', $tanggalHariIni)
            ->where(function($q) use ($namaGuru, $normGuru) {
                $q->where('guru', $namaGuru)
                  ->orWhereRaw("
                    LOWER(
                        REPLACE(REPLACE(REPLACE(REPLACE(guru, ' ', ''), '.', ''), ',', ''), '-', '')
                    ) = ?
                  ", [$normGuru]);
            })
            ->orderBy('jam_mulai')
            ->get()
            ->map(function($j) {
                return [
                    'mata_pelajaran' => $j->mata_pelajaran,
                    'guru'           => $j->guru,
                    'jam_mulai'      => $j->jam_mulai,
                    'jam_selesai'    => $j->jam_selesai,
                    'kelas'          => [
                        'nama_kelas' => $j->kelas->nama_kelas ?? '-'
                    ],
                ];
            });

        // ===== DATA TAMBAHAN =====
        $kelas = Kelas::where('guru_id', $guru->id)->first();
        $jumlahMuridKelas = $kelas ? $kelas->murids()->count() : 0;

        $muridQuery = $kelas ? Murid::where('kelas_id', $kelas->id) : Murid::query();
        $muridLaki = $muridQuery->where('jenis_kelamin', 'L')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->groupBy('bulan')->pluck('total', 'bulan')->toArray();

        $muridPerempuan = $muridQuery->where('jenis_kelamin', 'P')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->groupBy('bulan')->pluck('total', 'bulan')->toArray();

        $nilaiPerBulan = Nilai::where('guru_id', $guru->id)
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->groupBy('bulan')->pluck('total', 'bulan')->toArray();

        $labelsBulan = [];
        $dataLaki = [];
        $dataPerempuan = [];
        $dataNilai = [];

        for ($i = 1; $i <= 12; $i++) {
            $labelsBulan[] = date('F', mktime(0, 0, 0, $i, 1));
            $dataLaki[] = $muridLaki[$i] ?? 0;
            $dataPerempuan[] = $muridPerempuan[$i] ?? 0;
            $dataNilai[] = $nilaiPerBulan[$i] ?? 0;
        }

        return view('guru.dashboard.index', [
            'jumlahMuridKelas'   => $jumlahMuridKelas,
            'jumlahPerkembangan' => Perkembangan::where('guru_id', $guru->id)->count(),
            'jumlahNilai'        => Nilai::where('guru_id', $guru->id)->count(),
            'labelsBulan'        => $labelsBulan,
            'dataLaki'           => $dataLaki,
            'dataPerempuan'      => $dataPerempuan,
            'dataNilai'          => $dataNilai,
            'jadwalHariIni'      => $jadwalHariIni,
        ]);
    }

    // ========================
    // AJAX mendapatkan jadwal
    // ========================
    public function getJadwalTanggal($tanggal)
    {
        $guru = Auth::user()->guru ?? null;
        if (!$guru) return response()->json([], 403);

        $namaGuru = $guru->user->name;

        $normalize = function($s) {
            $s = preg_replace('/\b(Pak|Bpk|Bu|Ibu|Mr|Mrs|Ms)\b/iu', '', $s);
            $s = preg_replace('/[^\p{L}\p{N}]/u', '', $s);
            return Str::lower($s);
        };

        $normGuru = $normalize($namaGuru);

        $jadwal = Jadwal::with('kelas')
            ->whereDate('tanggal', $tanggal)
            ->where(function($q) use ($namaGuru, $normGuru) {
                $q->where('guru', $namaGuru)
                  ->orWhereRaw("
                    LOWER(
                        REPLACE(REPLACE(REPLACE(REPLACE(guru, ' ', ''), '.', ''), ',', ''), '-', '')
                    ) = ?
                  ", [$normGuru]);
            })
            ->orderBy('jam_mulai')
            ->get()
            ->map(function($j) {
                return [
                    'mata_pelajaran' => $j->mata_pelajaran,
                    'guru'           => $j->guru,
                    'jam_mulai'      => $j->jam_mulai,
                    'jam_selesai'    => $j->jam_selesai,
                    'kelas'          => [
                        'nama_kelas' => $j->kelas->nama_kelas ?? '-'
                    ],
                ];
            });

        return response()->json($jadwal->values());
    }
}
