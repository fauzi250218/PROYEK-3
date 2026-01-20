<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Nilai;
use App\Models\CatatanPerkembangan;

class ERaportController extends Controller
{
    /**
     * HALAMAN AWAL — MENAMPILKAN DAFTAR MURID SESUAI KELAS YANG DIA WALIKELAS
     */
    public function index()
    {
        // Ambil guru berdasarkan akun login
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        // Ambil kelas yang dia wali kelas in
        $kelas = Kelas::where('guru_id', $guru->id)->firstOrFail();

        // Ambil semua murid dalam kelas tersebut
        $murids = $kelas->murids()->orderBy('nama')->get();

        return view('guru.manajemen-kelas.kelas-binaan.eraport.index', [
            'guru' => $guru,
            'kelas' => $kelas,
            'murids' => $murids,
        ]);
    }



    /**
     * HALAMAN LIHAT RAPORT PER MURID
     */
    public function show($murid_id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $murid = Murid::with('kelas')->findOrFail($murid_id);

        // Pastikan murid tersebut berada di kelas wali kelas bersangkutan
        if ($murid->kelas->guru_id !== $guru->id) {
            abort(403, 'Anda tidak memiliki akses ke murid ini.');
        }

        // Ambil mata pelajaran dari jadwal kelas
        $mapels = $murid->kelas->jadwals()
            ->pluck('mata_pelajaran')
            ->unique()
            ->values();

        // Ambil semua nilai murid
        $nilai = Nilai::where('murid_id', $murid->id)->get();

        // Ambil catatan perkembangan per mapel
        $catatanMapel = [];
        foreach ($mapels as $mapel) {
            $catatanMapel[$mapel] = CatatanPerkembangan::where('murid_id', $murid->id)
                ->where('kategori', $mapel)
                ->latest()
                ->first();
        }

        return view('guru.manajemen-kelas.kelas-binaan.eraport.show', [
            'murid' => $murid,
            'mapels' => $mapels,
            'nilai' => $nilai,
            'catatanMapel' => $catatanMapel,
        ]);
    }



    /**
     * DOWNLOAD FORMAT PDF RAPORT
     */
    public function download($murid_id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        $murid = Murid::with('kelas')->findOrFail($murid_id);

        if ($murid->kelas->guru_id !== $guru->id) {
            abort(403, "Anda tidak memiliki akses ke murid ini.");
        }

        // Mapel
        $mapels = $murid->kelas->jadwals()
            ->pluck('mata_pelajaran')
            ->unique()
            ->values();

        // Nilai
        $nilai = Nilai::where('murid_id', $murid->id)->get();

        // Catatan perkembangan per mapel
        $catatanMapel = [];
        foreach ($mapels as $mapel) {
            $catatanMapel[$mapel] = CatatanPerkembangan::where('murid_id', $murid->id)
                ->where('kategori', $mapel)
                ->latest()
                ->first();
        }

        // Load file Blade PDF
        $pdf = Pdf::loadView('guru.manajemen-kelas.kelas-binaan.eraport.pdf', [
            'murid' => $murid,
            'mapels' => $mapels,
            'nilai' => $nilai,
            'catatanMapel' => $catatanMapel,
        ]);

        return $pdf->download('eraport-' . $murid->nama . '.pdf');
    }
}

