<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Murid;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Halaman index semua kelas
    public function index()
    {
        $kelas = Kelas::withCount('murids')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    // Form tambah kelas
    public function create()
    {
        return view('admin.kelas.create');
    }

    // Simpan kelas baru
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

    // Form edit kelas
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'));
    }

    // Update kelas
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

    // Hapus kelas
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    // ✅ Halaman kelola murid di kelas
    public function kelolaMurid($id)
    {
        $kelas = Kelas::with('murids')->findOrFail($id);

        // Murid yang belum masuk kelas ini
        $muridBelumMasukKelas = Murid::whereNull('kelas_id')->get();

        // Murid yang sudah masuk kelas ini
        $muridDalamKelas = $kelas->murids;

        return view('admin.kelas.kelola-murid', compact('kelas', 'muridBelumMasukKelas', 'muridDalamKelas'));
    }

    // Tambah murid ke kelas
    public function tambahMurid(Request $request, $id)
    {
        $request->validate(['murid_id' => 'required|exists:murids,id']);

        $murid = Murid::findOrFail($request->murid_id);
        $murid->kelas_id = $id;
        $murid->save();

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    // Hapus murid dari kelas
    public function hapusMurid($kelas_id, $murid_id)
    {
        $murid = Murid::findOrFail($murid_id);
        $murid->kelas_id = null;
        $murid->save();

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }

    // 🔹 Halaman khusus kelas 7, tab per subkelas 7A, 7B, …
    public function kelas7()
    {
        // Ambil kelas 7 saja (nama diawali "7") dan urutkan A-Z
        $kelas7 = Kelas::with('murids')
                       ->where('nama_kelas', 'like', '7%')
                       ->orderBy('nama_kelas')
                       ->get();

        return view('admin.kelas.kelas7', compact('kelas7'));
    }
}
