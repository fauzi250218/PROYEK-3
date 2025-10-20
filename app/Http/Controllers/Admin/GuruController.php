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
     * Tampilkan semua data guru
     */
    public function index()
    {
        // Sudah bisa pakai 'kelas' karena alias disediakan di model
        $guru = Guru::with(['user', 'kelas'])->get();

        return view('admin.guru.index', compact('guru'));
    }

    /**
     * Form tambah guru baru
     */
    public function create()
    {
        // Ambil kelas yang belum punya wali (guru)
        $kelas = Kelas::whereNull('guru_id')->get();
        return view('admin.guru.create', compact('kelas'));
    }

    /**
     * Simpan data guru baru
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

        // Buat akun user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'guru',
        ]);

        // Buat data guru
        $guru = Guru::create([
            'user_id'        => $user->id,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'mata_pelajaran' => $request->mata_pelajaran,
        ]);

        // Tetapkan kelas binaan (jika ada)
        if ($request->filled('kelas_id')) {
            Kelas::where('id', $request->kelas_id)->update(['guru_id' => $guru->id]);
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Guru berhasil ditambahkan dan ditetapkan sebagai wali kelas.');
    }

    /**
     * Form edit guru
     */
    public function edit(Guru $guru)
    {
        // Ambil kelas yang belum punya wali atau kelas milik guru ini
        $kelas = Kelas::whereNull('guru_id')
                      ->orWhere('guru_id', $guru->id)
                      ->get();

        return view('admin.guru.edit', compact('guru', 'kelas'));
    }

    /**
     * Update data guru
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

        // Update user
        $user = $guru->user;
        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Update data guru
        $guru->update([
            'jenis_kelamin'  => $request->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp,
            'mata_pelajaran' => $request->mata_pelajaran,
        ]);

        // Reset dan update kelas binaan
        Kelas::where('guru_id', $guru->id)->update(['guru_id' => null]);

        if ($request->filled('kelas_id')) {
            Kelas::where('id', $request->kelas_id)->update(['guru_id' => $guru->id]);
        }

        $guru->load('kelas');

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru dan kelas binaan berhasil diperbarui.');
    }

    /**
     * Hapus guru dan akun login-nya
     */
    public function destroy(Guru $guru)
    {
        // Lepaskan relasi kelas binaan
        Kelas::where('guru_id', $guru->id)->update(['guru_id' => null]);

        // Hapus akun user
        if ($guru->user) {
            $guru->user->delete();
        }

        // Hapus data guru
        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru dan akun login berhasil dihapus.');
    }
}
