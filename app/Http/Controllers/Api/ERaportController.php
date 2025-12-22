<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Murid;
use App\Models\Nilai;
use App\Models\CatatanPerkembangan;
use Illuminate\Http\Request;

class ERaportController extends Controller
{
    // =============================
    // GET DATA ERAPORT (JSON)
    // =============================
    public function show($murid_id)
    {
        $murid = Murid::with('kelas')->findOrFail($murid_id);

        $mapels = $murid->kelas->jadwals()
            ->pluck('mata_pelajaran')
            ->unique()
            ->values();

        $nilai = Nilai::where('murid_id', $murid->id)->get();

        $data = [];

        foreach ($mapels as $mapel) {
            $nilaiMapel = $nilai->where('mata_pelajaran', $mapel)->first();

            $catatan = CatatanPerkembangan::where('murid_id', $murid->id)
                ->where('kategori', $mapel)
                ->latest()
                ->first();

            $data[] = [
                'mapel' => $mapel,
                'nilai' => $nilaiMapel->rata_rata ?? '-',
                'catatan' => $catatan->catatan ?? '-',
            ];
        }

        return response()->json([
            'success' => true,
            'siswa' => [
                'nama' => $murid->nama,
                'nis' => $murid->nis,
                'kelas' => $murid->kelas->nama_kelas,
            ],
            'rapor' => $data,
        ]);
    }

    // =============================
    // DOWNLOAD PDF ERAPORT
    // =============================
    public function download($murid_id)
    {
        $murid = Murid::with('kelas')->findOrFail($murid_id);

        $mapels = $murid->kelas->jadwals()
            ->pluck('mata_pelajaran')
            ->unique()
            ->values();

        $nilai = Nilai::where('murid_id', $murid->id)->get();

        $catatanMapel = [];
        foreach ($mapels as $mapel) {
            $catatanMapel[$mapel] = CatatanPerkembangan::where('murid_id', $murid->id)
                ->where('kategori', $mapel)
                ->latest()
                ->first();
        }

        $pdf = Pdf::loadView(
            'guru.manajemen-kelas.kelas-binaan.eraport.pdf',
            [
                'murid' => $murid,
                'mapels' => $mapels,
                'nilai' => $nilai,
                'catatanMapel' => $catatanMapel,
            ]
        );

        return $pdf->download('eraport-' . $murid->nama . '.pdf');
    }
}
