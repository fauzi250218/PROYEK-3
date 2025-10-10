<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Murid;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // ✅ Halaman utama data kelas
    public function index()
    {
        $kelas = Kelas::withCount('murids')->get()->groupBy(function ($item) {
            return substr($item->nama_kelas, 0, 1);
        });

        return view('admin.kelas.index', compact('kelas'));
    }

    // ✅ Form tambah kelas
    public function create()
    {
        return view('admin.kelas.create');
    }

    // ✅ Simpan kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Kelas::create($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // ✅ Form edit kelas
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    // ✅ Update kelas
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    // ✅ Hapus kelas
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    // ✅ Kelola murid di kelas tertentu
    public function kelolaMurid($id)
    {
        $kelas = Kelas::with('murids')->findOrFail($id);

        // Ambil semua siswa yang belum masuk kelas
        $muridBelumMasukKelas = Murid::whereNull('kelas_id')
            ->orderBy('nama')
            ->get()
            ->groupBy(function ($murid) {
                // kelompokkan berdasarkan angka pertama dari NIS (asumsi 7, 8, 9)
                return substr($murid->nis, 0, 1);
            });

        $muridDalamKelas = $kelas->murids;

        return view('admin.kelas.kelola-murid', compact('kelas', 'muridBelumMasukKelas', 'muridDalamKelas'));
    }

    // ✅ Tambah beberapa murid ke kelas
    public function tambahMurid(Request $request, $id)
    {
        $request->validate([
            'murid_ids' => 'required|array',
        ]);

        Murid::whereIn('id', $request->murid_ids)->update(['kelas_id' => $id]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    // ✅ Hapus murid dari kelas
    public function hapusMurid($kelas_id, $murid_id)
    {
        $murid = Murid::findOrFail($murid_id);
        $murid->kelas_id = null;
        $murid->save();

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
}
