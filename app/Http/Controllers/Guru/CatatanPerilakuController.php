<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\CatatanPerilaku;
use App\Models\Murid;
use App\Models\Kelas;
use Illuminate\Http\Request;

class CatatanPerilakuController extends Controller
{
    // Tampilkan semua catatan perilaku dalam kelas
    public function index($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $catatan = CatatanPerilaku::where('kelas_id', $kelasId)
                    ->with('murid')
                    ->latest()
                    ->get();

        return view('guru.manajemen-kelas.catatan', compact('kelas', 'catatan'));
    }

    // Tampilkan form tambah catatan
    public function create($kelasId)
    {
        $kelas = Kelas::findOrFail($kelasId);
        $muridList = Murid::where('kelas_id', $kelasId)->get();

        return view('guru.manajemen-kelas.catatan-create', compact('kelas', 'muridList'));
    }

    // Simpan catatan perilaku baru
    public function store(Request $request, $kelasId)
    {
        $request->validate([
            'murid_id' => 'required|exists:murids,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|string|in:Positif,Negatif,Netral',
            'tanggal' => 'required|date',
        ]);

        CatatanPerilaku::create([
            'murid_id' => $request->murid_id,
            'kelas_id' => $kelasId,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->route('guru.kelas.binaan.catatan.index', $kelasId)
                         ->with('success', 'Catatan perilaku berhasil ditambahkan.');
    }
}
