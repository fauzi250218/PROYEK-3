<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class MuridController extends Controller
{
    // ==============================
    //  Tampilkan semua murid
    // ==============================
    public function index()
    {
        // Ambil semua data murid beserta relasi kelas
        $murids = Murid::with('kelas')->orderBy('nama')->get();

        // Kelompokkan murid berdasarkan jenjang (dari nama_kelas)
        $muridPerJenjang = $murids->groupBy(function ($murid) {
            // misal nama_kelas = "7A" → ambil angka 7
            return substr($murid->kelas->nama_kelas ?? 'Lainnya', 0, 1);
        });

        return view('admin.murid.index', compact('muridPerJenjang'));
    }

    // ==============================
    //  Form tambah murid
    // ==============================
    public function create()
    {
        $kelas = Kelas::all(); // ambil daftar kelas untuk dropdown
        return view('admin.murid.create', compact('kelas'));
    }

    // ==============================
    //  Simpan murid baru
    // ==============================
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:murids,nis',
            'nama' => 'required',
            'email' => 'required|email|unique:murids,email',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required',
            'kata_sandi' => 'required|min:6',
        ]);

        Murid::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'email' => $request->email,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'nomer_whatsapp' => $request->nomer_whatsapp,
        ]);

        return redirect()->route('admin.murid.index')->with('success', 'Data murid berhasil ditambahkan');
    }

    // ==============================
    //  Form edit murid
    // ==============================
    public function edit($id)
    {
        $murid = Murid::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.murid.edit', compact('murid', 'kelas'));
    }

    // ==============================
    //  Update murid
    // ==============================
    public function update(Request $request, $id)
    {
        $murid = Murid::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:murids,nis,' . $id,
            'nama' => 'required',
            'email' => 'required|email|unique:murids,email,' . $id,
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required',
        ]);

        $murid->update([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'email' => $request->email,
            'kelas_id' => $request->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'kata_sandi' => $request->filled('kata_sandi')
                ? Hash::make($request->kata_sandi)
                : $murid->kata_sandi,
        ]);

        return redirect()->route('admin.murid.index')->with('success', 'Data murid berhasil diperbarui');
    }

    // ==============================
    //  Hapus murid
    // ==============================
    public function destroy($id)
    {
        $murid = Murid::findOrFail($id);
        $murid->delete();

        return redirect()->route('admin.murid.index')->with('success', 'Data murid berhasil dihapus');
    }
}
