<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Nilai;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * 1️⃣ Tampilkan semua kelas (file: semua-kelas.blade.php)
     */
    public function semuaKelas()
    {
        $kelasList = Kelas::with(['guru.user'])->get();
        return view('guru.nilai.semua-kelas', compact('kelasList'));
    }

    /**
     * 2️⃣ Tampilkan daftar murid dari kelas yang dipilih (file: index.blade.php)
     */
    public function index($id)
    {
        $kelas = Kelas::with(['guru.user', 'murids'])->findOrFail($id);
        return view('guru.nilai.index', compact('kelas'));
    }

    /**
     * 3️⃣ Tampilkan detail nilai untuk satu murid (file: detail.blade.php)
     */
    public function detail($id)
    {
        $murid = Murid::with(['kelas', 'nilai'])->findOrFail($id);
        $nilaiList = Nilai::where('murid_id', $id)->get();
        return view('guru.nilai.detail', compact('murid', 'nilaiList'));
    }

    /**
     * 4️⃣ Form tambah nilai (file: create.blade.php)
     */
    public function create($id)
    {
        $murid = Murid::with('kelas')->findOrFail($id);

        // Ambil guru yang sedang login
        $guru = Auth::user()->guru;

        // Hanya mata pelajaran milik guru login yang akan muncul
        $mapelList = collect([$guru->mata_pelajaran]);

        return view('guru.nilai.create', compact('murid', 'mapelList'));
    }

    /**
     * Simpan nilai baru
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'mata_pelajaran' => 'required|string|max:100',
            'keterangan' => 'required|string|in:tugas,ulangan_harian,uts,uas',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $murid = Murid::findOrFail($id);
        $guruId = Auth::user()->guru->id ?? null;

        Nilai::create([
            'murid_id' => $murid->id,
            'guru_id' => $guruId,
            'kelas_id' => $murid->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            $request->keterangan => $request->nilai,
        ]);

        return redirect()->route('guru.nilai.detail', $murid->id)
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * 5️⃣ Form edit nilai (file: edit.blade.php)
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $guru = Auth::user()->guru;

        // Hanya tampilkan mapel guru login
        $mapelList = collect([$guru->mata_pelajaran]);

        return view('guru.nilai.edit', compact('nilai', 'mapelList'));
    }

    /**
     * Update nilai
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'mata_pelajaran' => 'required|string|max:100',
            'keterangan' => 'required|string|in:tugas,ulangan_harian,uts,uas',
            'nilai' => 'required|numeric|min:0|max:100',
        ]);

        $nilai = Nilai::findOrFail($id);

        $nilai->update([
            'mata_pelajaran' => $request->mata_pelajaran,
            'tugas' => null,
            'ulangan_harian' => null,
            'uts' => null,
            'uas' => null,
            $request->keterangan => $request->nilai,
        ]);

        return redirect()->route('guru.nilai.detail', $nilai->murid_id)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Hapus nilai
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return back()->with('success', 'Nilai berhasil dihapus.');
    }
}
