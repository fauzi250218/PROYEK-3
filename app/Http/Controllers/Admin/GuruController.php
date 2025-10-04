<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::all();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:100',
            'email'          => 'required|email|unique:guru',
            'kelas'          => 'nullable|string|max:50',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'kata_sandi'     => 'required|min:6',
            'nomer_whatsapp' => 'nullable|string|max:20',
            'mata_pelajaran' => 'nullable|string|max:100',
        ]);

        Guru::create($request->all());

        return redirect()->route('admin.guru.index')
                         ->with('success', 'Guru berhasil ditambahkan');
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama'           => 'required|string|max:100',
            'email'          => 'required|email|unique:guru,email,'.$guru->id,
            'kelas'          => 'nullable|string|max:50',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'nomer_whatsapp' => 'nullable|string|max:20',
            'mata_pelajaran' => 'nullable|string|max:100',
        ]);

        if ($request->filled('kata_sandi')) {
            $guru->update($request->all());
        } else {
            $guru->update($request->except('kata_sandi'));
        }

        return redirect()->route('admin.guru.index')
                         ->with('success', 'Data guru berhasil diupdate');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('admin.guru.index')
                         ->with('success', 'Data guru berhasil dihapus');
    }
}
