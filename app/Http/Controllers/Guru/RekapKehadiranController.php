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
     * REKAP KEHADIRAN SISWA (PER KELAS)
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
         | 3. AMBIL KELAS + RELASI (LENGKAP)
         =============================== */
        $kelas = Kelas::with([
            'murids',
            'sesi.jadwal',      // 🔥 WAJIB
            'sesi.kehadiran'
        ])
            ->where('id', $kelasId)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        /* ===============================
         | 4. DAFTAR TAHUN (DINAMIS)
         =============================== */
        $daftarTahun = $kelas->sesi
            ->map(fn($sesi) => Carbon::parse($sesi->tanggal)->year)
            ->unique()
            ->sortDesc()
            ->values();

        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([now()->year]);
        }

        /* ===============================
         | 5. REKAP PER SISWA
         =============================== */
        $rekap = [];

        foreach ($kelas->murids as $murid) {

            $hadir = $izin = $sakit = $alpha = 0;

            foreach ($kelas->sesi as $sesi) {

                $tanggal = Carbon::parse($sesi->tanggal);

                if ($tanggal->year !== $tahun) continue;
                if ($bulan !== 'all' && $tanggal->month !== (int) $bulan) continue;

                $absen = $sesi->kehadiran
                    ->where('murid_id', $murid->id)
                    ->first();

                if (!$absen) continue;

                match ($absen->status) {
                    'H' => $hadir++,
                    'I' => $izin++,
                    'S' => $sakit++,
                    'A' => $alpha++,
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
        $rataRataKehadiran = round(collect($rekap)->avg('persen') ?? 0);

        /* ===============================
         | 7. LABEL PERIODE
         =============================== */
        $periodeAktif = $bulan === 'all'
            ? "Tahun $tahun"
            : Carbon::createFromDate($tahun, (int) $bulan, 1)->translatedFormat('F Y');

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
     * DETAIL KEHADIRAN PER SISWA (REKAP MAPEL)
     * =====================================================
     */
    public function detail(Request $request, $kelasId, $muridId)
    {
        /* ===============================
         | VALIDASI GURU
         =============================== */
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        /* ===============================
         | AMBIL DATA (AMAN)
         =============================== */
        $kelas = Kelas::with([
            'sesi.jadwal',      // 🔥 WAJIB
            'sesi.kehadiran'
        ])
            ->where('id', $kelasId)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $murid = Murid::where('id', $muridId)
            ->where('kelas_id', $kelas->id)
            ->firstOrFail();

        /* ===============================
         | RETURN VIEW DETAIL
         =============================== */
        return view(
            'guru.manajemen-kelas.kelas-binaan.detail-kehadiran',
            compact('kelas', 'murid')
        );
    }
}
