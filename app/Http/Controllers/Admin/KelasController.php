<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\User;

class KelasController extends Controller
{
    /**
     * 🔹 Menampilkan daftar semua kelas (dikelompokkan per jenjang)
     */
    public function index()
    {
        // Ambil semua kelas dengan wali dan muridnya
        $kelas = Kelas::with(['wali', 'murids'])
            ->get()
            ->groupBy(function ($item) {
                // Ambil jenjang dari nama kelas (contoh: "7A" → 7)
                return substr($item->nama_kelas, 0, 1);
            });

        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * 🔹 Form Tambah Kelas
     */
    public function create()
    {
        // Ambil semua user dengan role guru yang belum menjadi wali kelas
        $guru = User::where('role', 'guru')
                    ->whereDoesntHave('kelas') // butuh relasi di User.php -> kelas()
                    ->get();

        return view('admin.kelas.create', compact('guru'));
    }

    /**
     * 🔹 Simpan Kelas Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'user_id'    => 'nullable|exists:users,id',
            'deskripsi'  => 'nullable|string',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'user_id'    => $request->user_id,
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * 🔹 Form Edit Kelas
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Guru yang bisa dipilih: guru tanpa kelas atau yang sudah jadi wali kelas ini
        $guru = User::where('role', 'guru')
                    ->whereDoesntHave('kelas')
                    ->orWhere('id', $kelas->user_id)
                    ->get();

        return view('admin.kelas.edit', compact('kelas', 'guru'));
    }

    /**
     * 🔹 Update Data Kelas
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'user_id'    => 'nullable|exists:users,id',
            'deskripsi'  => 'nullable|string',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'user_id'    => $request->user_id,
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()->route('admin.kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * 🔹 Hapus Kelas
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * 🔹 Halaman Kelola Murid per Kelas
     */
    public function kelolaMurid($id)
    {
        $kelas = Kelas::with(['murids', 'wali'])->findOrFail($id);

        // Ambil murid yang belum masuk kelas
        $muridBelumMasukKelas = Murid::whereNull('kelas_id')
            ->orderBy('nama')
            ->get()
            ->groupBy(function ($murid) {
                // Kelompokkan berdasarkan jenjang (misal dari NIS: 7XXXX → kelas 7)
                return substr($murid->nis, 0, 1);
            });

        $muridDalamKelas = $kelas->murids;

        return view('admin.kelas.kelola-murid', compact('kelas', 'muridBelumMasukKelas', 'muridDalamKelas'));
    }

    /**
     * 🔹 Tambahkan Murid ke Kelas
     */
    public function tambahMurid(Request $request, $id)
    {
        $request->validate([
            'murid_ids' => 'required|array',
        ]);

        Murid::whereIn('id', $request->murid_ids)->update(['kelas_id' => $id]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    /**
     * 🔹 Hapus Murid dari Kelas
     */
    public function hapusMurid($kelas_id, $murid_id)
    {
        $murid = Murid::findOrFail($murid_id);
        $murid->update(['kelas_id' => null]);

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
}
