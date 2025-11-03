<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MuridController extends Controller
{
    // ==============================
    //  📋 Tampilkan semua murid per jenjang
    // ==============================
    public function index()
    {
        // Ambil semua data murid + relasi kelas
        $murids = Murid::with('kelas')->orderBy('nama')->get();

        // Kelompokkan berdasarkan angka pertama dari nama_kelas (contoh: "7A" → 7)
        $muridPerJenjang = $murids->groupBy(function ($murid) {
            return substr($murid->kelas->nama_kelas ?? 'Lainnya', 0, 1);
        });

        // Kirim ke view
        return view('admin.murid.index', compact('muridPerJenjang'));
    }

    // ==============================
    //  ➕ Form tambah murid
    // ==============================
    public function create()
    {
        $kelas = Kelas::all(); // daftar kelas untuk dropdown
        return view('admin.murid.create', compact('kelas'));
    }

    // ==============================
    //  💾 Simpan murid baru
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'nis'            => 'required|unique:murids,nis',
            'nama'           => 'required|string|max:100',
            'email'          => 'required|email|unique:murids,email',
            'kelas_id'       => 'required|exists:kelas,id',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'kata_sandi'     => 'required|min:6',
            'foto_profil'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nomer_whatsapp' => 'nullable|string|max:20',
        ]);

        $path = null;
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('foto_murid', 'public');
        }

        Murid::create([
            'nis'            => $request->nis,
            'nama'           => $request->nama,
            'email'          => $request->email,
            'kelas_id'       => $request->kelas_id,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'kata_sandi'     => Hash::make($request->kata_sandi),
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'foto_profil'    => $path,
        ]);

        return redirect()->route('admin.murid.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    // ==============================
    //  ✏️ Form edit murid
    // ==============================
    public function edit($id)
    {
        $murid = Murid::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.murid.edit', compact('murid', 'kelas'));
    }

    // ==============================
    //  🔄 Update murid
    // ==============================
    public function update(Request $request, $id)
    {
        $murid = Murid::findOrFail($id);

        $request->validate([
            'nis'            => 'required|unique:murids,nis,' . $id,
            'nama'           => 'required|string|max:100',
            'email'          => 'required|email|unique:murids,email,' . $id,
            'kelas_id'       => 'required|exists:kelas,id',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'foto_profil'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nomer_whatsapp' => 'nullable|string|max:20',
        ]);

        // Update foto jika diunggah baru
        $path = $murid->foto_profil;
        if ($request->hasFile('foto_profil')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('foto_profil')->store('foto_murid', 'public');
        }

        $murid->update([
            'nis'            => $request->nis,
            'nama'           => $request->nama,
            'email'          => $request->email,
            'kelas_id'       => $request->kelas_id,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'kata_sandi'     => $request->filled('kata_sandi')
                ? Hash::make($request->kata_sandi)
                : $murid->kata_sandi,
            'foto_profil'    => $path,
        ]);

        return redirect()->route('admin.murid.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    // ==============================
    //  🗑️ Hapus murid
    // ==============================
    public function destroy($id)
    {
        $murid = Murid::findOrFail($id);

        if ($murid->foto_profil && Storage::disk('public')->exists($murid->foto_profil)) {
            Storage::disk('public')->delete($murid->foto_profil);
        }

        $murid->delete();

        return redirect()->route('admin.murid.index')->with('success', 'Data siswa berhasil dihapus');
    }
}
