<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with(['user', 'kelas'])->get();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        $kelas = Kelas::whereNull('guru_id')->get();
        return view('admin.guru.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6',
            'kelas_id'       => 'nullable|exists:kelas,id',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'nomer_whatsapp' => 'nullable|string|max:20',
            'mata_pelajaran' => 'nullable|string|max:100',
            'foto_profil'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_profil')) {
            $fotoPath = $request->file('foto_profil')->store('foto_guru', 'public');
        }

        $guru = Guru::create([
            'user_id'        => $user->id,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'mata_pelajaran' => $request->mata_pelajaran,
            'foto_profil'    => $fotoPath,
        ]);

        if ($request->filled('kelas_id')) {
            Kelas::where('id', $request->kelas_id)->update(['guru_id' => $guru->id]);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        $kelas = Kelas::whereNull('guru_id')
                      ->orWhere('guru_id', $guru->id)
                      ->get();

        return view('admin.guru.edit', compact('guru', 'kelas'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'name'           => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email,' . $guru->user_id,
            'password'       => 'nullable|min:6',
            'kelas_id'       => 'nullable|exists:kelas,id',
            'jenis_kelamin'  => 'required|in:Laki-laki,Perempuan',
            'nomer_whatsapp' => 'nullable|string|max:20',
            'mata_pelajaran' => 'nullable|string|max:100',
            'foto_profil'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = $guru->user;
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($guru->foto_profil && Storage::disk('public')->exists($guru->foto_profil)) {
                Storage::disk('public')->delete($guru->foto_profil);
            }
            $fotoPath = $request->file('foto_profil')->store('foto_guru', 'public');
            $guru->foto_profil = $fotoPath;
        }

        $guru->update([
            'jenis_kelamin'  => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'mata_pelajaran' => $request->mata_pelajaran,
            'foto_profil'    => $guru->foto_profil,
        ]);

        Kelas::where('guru_id', $guru->id)->update(['guru_id' => null]);
        if ($request->filled('kelas_id')) {
            Kelas::where('id', $request->kelas_id)->update(['guru_id' => $guru->id]);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        if ($guru->foto_profil && Storage::disk('public')->exists($guru->foto_profil)) {
            Storage::disk('public')->delete($guru->foto_profil);
        }

        Kelas::where('guru_id', $guru->id)->update(['guru_id' => null]);
        if ($guru->user) $guru->user->delete();
        $guru->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
