<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;

class GuruController extends Controller
{
    /**
     * 🔹 Tampilkan semua data guru
     */
    public function index()
    {
        $guru = Guru::with(['user', 'user.kelas'])->get(); // tambahkan user.kelas agar kolom kelas muncul
        return view('admin.guru.index', compact('guru'));
    }

    /**
     * 🔹 Form tambah guru baru
     */
    public function create()
    {
        // hanya kelas yang belum punya wali
        $kelas = Kelas::whereNull('user_id')->get();
        return view('admin.guru.create', compact('kelas'));
    }

    /**
     * 🔹 Simpan data guru baru
     */
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
        ]);

        // buat akun user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        // buat data guru
        $guru = Guru::create([
            'user_id'        => $user->id,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'mata_pelajaran' => $request->mata_pelajaran,
        ]);

        // tetapkan kelas jika dipilih
        if ($request->kelas_id) {
            Kelas::find($request->kelas_id)->update(['user_id' => $user->id]);
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru berhasil ditambahkan dan ditetapkan sebagai wali kelas.');
    }

    /**
     * 🔹 Form edit guru
     */
    public function edit(Guru $guru)
    {
        $kelas = Kelas::whereNull('user_id')
                      ->orWhere('user_id', $guru->user_id)
                      ->get();

        return view('admin.guru.edit', compact('guru', 'kelas'));
    }

    /**
     * 🔹 Update data guru
     */
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
        ]);

        // update user
        $user = $guru->user;
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $user->password,
        ]);

        // update guru
        $guru->update([
            'jenis_kelamin'  => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'mata_pelajaran' => $request->mata_pelajaran,
        ]);

        // update kelas (hapus wali lama dulu)
        Kelas::where('user_id', $guru->user_id)->update(['user_id' => null]);

        if ($request->kelas_id) {
            Kelas::find($request->kelas_id)->update(['user_id' => $guru->user_id]);
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * 🔹 Hapus guru dan akun login-nya
     */
    public function destroy(Guru $guru)
    {
        Kelas::where('user_id', $guru->user_id)->update(['user_id' => null]);

        if ($guru->user) {
            $guru->user->delete();
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru dan akun login berhasil dihapus.');
    }
}
