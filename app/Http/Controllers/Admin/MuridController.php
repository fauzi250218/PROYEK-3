<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Kelas;
use Illuminate\Http\Request;

class MuridController extends Controller
{
    // Menampilkan daftar murid per kelas
    public function index()
    {
        $kelasList = Kelas::with('murids')->get();

        // Siapkan array untuk Blade: ['nama_kelas' => Collection of murid]
        $muridPerKelas = [];
        foreach ($kelasList as $kelas) {
            $muridPerKelas[$kelas->nama_kelas] = $kelas->murids;
        }

        return view('admin.murid.index', compact('muridPerKelas'));
    }

    // Form tambah murid baru
    public function create()
    {
        $kelasList = Kelas::all();
        return view('admin.murid.create', compact('kelasList'));
    }

    // Simpan murid baru
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:murids,nis',
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'nomer_whatsapp' => 'nullable|string|max:20',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        Murid::create($request->all());

        return redirect()->route('admin.murid.index')->with('success', 'Murid berhasil ditambahkan.');
    }

    // Form edit murid
    public function edit($id)
    {
        $murid = Murid::findOrFail($id);
        $kelasList = Kelas::all();
        return view('admin.murid.edit', compact('murid', 'kelasList'));
    }

    // Update murid
    public function update(Request $request, $id)
    {
        $request->validate([
            'nis' => 'required|unique:murids,nis,' . $id,
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'nomer_whatsapp' => 'nullable|string|max:20',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $murid = Murid::findOrFail($id);
        $murid->update($request->all());

        return redirect()->route('admin.murid.index')->with('success', 'Data murid berhasil diperbarui.');
    }

    // Hapus murid
    public function destroy($id)
    {
        $murid = Murid::findOrFail($id);
        $murid->delete();

        return redirect()->route('admin.murid.index')->with('success', 'Murid berhasil dihapus.');
    }

    // Pindahkan murid ke kelas tertentu (opsional)
    public function pindahKelas(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $murid = Murid::findOrFail($id);
        $murid->kelas_id = $request->kelas_id;
        $murid->save();

        return redirect()->back()->with('success', 'Murid berhasil dipindahkan ke kelas baru.');
    }
}
