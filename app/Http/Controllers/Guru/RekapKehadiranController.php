<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Murid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class RekapKehadiranController extends Controller
{
    /**
     * =====================================================
     * REKAP KEHADIRAN SISWA (SEMUA GURU / PER KELAS)
     * =====================================================
     */
    public function index(Request $request, $kelasId)
    {
        /* ===============================
         | 1. VALIDASI GURU LOGIN
         =============================== */
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        /* ===============================
         | 2. PARAMETER FILTER
         =============================== */
        $bulan = $request->get('bulan', 'all');
        $tahun = (int) $request->get('tahun', now()->year);

        /* ===============================
         | 3. AMBIL KELAS (TANPA FILTER GURU)
         |    → PRESENSI DIGABUNG SEMUA GURU
         =============================== */
        $kelas = Kelas::with([
            'murids',
            'sesi.jadwal',
            'sesi.kehadiran'
        ])->findOrFail($kelasId);

        /* ===============================
         | 4. DAFTAR TAHUN (DINAMIS DARI SESI)
         =============================== */
        $daftarTahun = $kelas->sesi
            ->map(fn($s) => Carbon::parse($s->tanggal)->year)
            ->unique()
            ->sortDesc()
            ->values();

        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([now()->year]);
        }

        /* ===============================
         | 5. REKAP KEHADIRAN PER SISWA
         =============================== */
        $rekap = [];

        foreach ($kelas->murids as $murid) {

            $hadir = 0;
            $izin  = 0;
            $sakit = 0;
            $alpha = 0;

            foreach ($kelas->sesi as $sesi) {

                if (!$sesi->tanggal) continue;

                $tanggal = Carbon::parse($sesi->tanggal);

                if ($tanggal->year !== $tahun) continue;
                if ($bulan !== 'all' && $tanggal->month !== (int)$bulan) continue;

                $absen = $sesi->kehadiran
                    ->where('murid_id', $murid->id)
                    ->first();

                if (!$absen) continue;

                match ($absen->status) {
                    'H' => $hadir++,
                    'I' => $izin++,
                    'S' => $sakit++,
                    'A' => $alpha++,
                    default => null
                };
            }

            $total  = $hadir + $izin + $sakit + $alpha;
            $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;

            $rekap[] = [
                'murid_id' => $murid->id,
                'nama'     => $murid->nama,
                'hadir'    => $hadir,
                'izin'     => $izin,
                'sakit'    => $sakit,
                'alpha'    => $alpha,
                'total'    => $total,
                'persen'   => $persen,
            ];
        }

        /* ===============================
         | 6. STATISTIK GLOBAL
         =============================== */
        $totalSiswa = count($rekap);
        $rataRataKehadiran = $totalSiswa > 0
            ? round(collect($rekap)->avg('persen'))
            : 0;

        /* ===============================
         | 7. LABEL PERIODE
         =============================== */
        $periodeAktif = $bulan === 'all'
            ? "Tahun $tahun"
            : Carbon::createFromDate($tahun, (int)$bulan, 1)
            ->translatedFormat('F Y');

        /* ===============================
         | 8. RETURN VIEW
         =============================== */
        return view(
            'guru.manajemen-kelas.kelas-binaan.kehadiran',
            compact(
                'kelas',
                'rekap',
                'totalSiswa',
                'rataRataKehadiran',
                'periodeAktif',
                'daftarTahun'
            )
        );
    }

    /**
     * =====================================================
     * DETAIL KEHADIRAN PER SISWA
     * (SEMUA SESI / SEMUA GURU)
     * =====================================================
     */
    public function detail(Request $request, $kelasId, $muridId)
    {
        /* ===============================
         | VALIDASI GURU LOGIN
         =============================== */
        Guru::where('user_id', Auth::id())->firstOrFail();

        /* ===============================
         | AMBIL DATA KELAS + SESI
         =============================== */
        $kelas = Kelas::with([
            'sesi.jadwal',
            'sesi.kehadiran'
        ])->findOrFail($kelasId);

        $murid = Murid::where('id', $muridId)
            ->where('kelas_id', $kelas->id)
            ->firstOrFail();

        return view(
            'guru.manajemen-kelas.kelas-binaan.detail-kehadiran',
            compact('kelas', 'murid')
        );
    }
}
