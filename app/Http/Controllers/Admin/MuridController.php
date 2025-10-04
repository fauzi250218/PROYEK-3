<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Murid;
use Illuminate\Support\Facades\Hash;

class MuridController extends Controller
{
    // Menampilkan semua murid
    public function index()
    {
        $murids = Murid::all();
        return view('admin.murid.index', compact('murids'));
    }

    // Form tambah murid
    public function create()
    {
        return view('admin.murid.create');
    }

    // Simpan murid baru
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:murids,nis',
            'nama' => 'required',
            'email' => 'required|email|unique:murids,email',
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
            'kata_sandi' => 'required|min:6',
        ]);

        Murid::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'email' => $request->email,
            'kelas' => $request->kelas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kata_sandi' => Hash::make($request->kata_sandi),
            'nomer_whatsapp' => $request->nomer_whatsapp,
        ]);

        return redirect()->route('admin.murid.index')->with('success', 'Data murid berhasil ditambahkan');
    }

    // Form edit murid
    public function edit($id)
    {
        $murid = Murid::findOrFail($id);
        return view('admin.murid.edit', compact('murid'));
    }

    // Update murid
    public function update(Request $request, $id)
    {
        $murid = Murid::findOrFail($id);

        $request->validate([
            'nis' => 'required|unique:murids,nis,' . $id,
            'nama' => 'required',
            'email' => 'required|email|unique:murids,email,' . $id,
            'kelas' => 'required',
            'jenis_kelamin' => 'required',
        ]);

        $murid->nis = $request->nis;
        $murid->nama = $request->nama;
        $murid->email = $request->email;
        $murid->kelas = $request->kelas;
        $murid->jenis_kelamin = $request->jenis_kelamin;
        $murid->nomer_whatsapp = $request->nomer_whatsapp;

        if ($request->filled('kata_sandi')) {
            $murid->kata_sandi = Hash::make($request->kata_sandi);
        }

        $murid->save();

        return redirect()->route('admin.murid.index')->with('success', 'Data murid berhasil diperbarui');
    }

    // Hapus murid
    public function destroy($id)
    {
        $murid = Murid::findOrFail($id);
        $murid->delete();

        return redirect()->route('admin.murid.index')->with('success', 'Data murid berhasil dihapus');
    }
}
