<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Murid;
use App\Models\Kelas;
use App\Models\ValidasiMurid;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MuridController extends Controller
{
    // ==========================
    // LOGIN MURID DENGAN GOOGLE
    // ==========================
    public function googleLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        // Cari murid berdasarkan email Google
        $murid = Murid::where('email', $validated['email'])->first();

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Email belum terdaftar, silakan registrasi terlebih dahulu.',
            ], 404);
        }

        // 🔐 BUAT TOKEN SAMA SEPERTI LOGIN MANUAL
        $token = $murid->createToken('murid-google-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Google berhasil!',
            'token' => $token, // 🔥 WAJIB
            'data' => [
                'id' => $murid->id,
                'nama' => $murid->nama,
                'email' => $murid->email,
                'kelas' => $murid->kelas ? $murid->kelas->nama_kelas : null,
                'nomer_whatsapp' => $murid->nomer_whatsapp,
                'jenis_kelamin' => $murid->jenis_kelamin,
                'foto_profil' => $murid->foto_profil,
            ],
        ], 200);
    }

    // ==========================
    // REGISTER MURID BARU (WITH GOOGLE VALIDASI NIS)
    // ==========================
    public function googleRegisterValidate(Request $request)
    {
        // ✅ Validasi input dasar
        $validated = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email',
            'foto' => 'nullable|string',
            'nis'  => 'required|string',
        ]);

        // 🔍 Cek apakah NIS terdaftar di tabel validasi_murids
        $validNis = DB::table('validasi_murids')->where('nis', $validated['nis'])->first();
        if (!$validNis) {
            return response()->json([
                'success' => false,
                'message' => 'NIS tidak terdaftar. Hubungi wali kelas untuk verifikasi.',
            ], 404);
        }

        // 🔍 Cek apakah NIS sudah punya akun
        $nisExists = Murid::where('nis', $validated['nis'])->first();
        if ($nisExists) {
            return response()->json([
                'success' => false,
                'message' => 'NIS ini sudah terdaftar dengan akun lain.',
            ], 409);
        }

        // 🔍 Cek apakah email sudah dipakai
        $emailExists = Murid::where('email', $validated['email'])->first();
        if ($emailExists) {
            return response()->json([
                'success' => false,
                'message' => 'Email ini sudah terdaftar dengan akun lain.',
            ], 409);
        }

        // 🔐 Default jenis kelamin
        $defaultJenisKelamin = 'Laki-laki';

        // ✨ Buat akun murid baru
        $murid = Murid::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'foto_profil' => $validated['foto'] ?? null,
            'kelas_id' => $validNis->kelas_id,
            'jenis_kelamin' => $defaultJenisKelamin,
            'nomer_whatsapp' => null,
            'kata_sandi' => bcrypt('password'),
            'nis' => $validated['nis'],
        ]);

        // 🔔 Buat notifikasi awal
        $murid->notifications()->create([
            'title' => 'Akun Google Berhasil Dibuat',
            'message' => 'Password default Anda adalah "password". Silakan ubah jika perlu.',
            'is_read' => false,
        ]);

        // 🔐 BUAT TOKEN LANGSUNG SETELAH REGISTER
        $token = $murid->createToken('murid-google-register')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Akun berhasil dibuat dan login otomatis!',
            'token' => $token, // 🔥 PENTING
            'data' => [
                'id' => $murid->id,
                'nama' => $murid->nama,
                'email' => $murid->email,
                'kelas' => $murid->kelas ? $murid->kelas->nama_kelas : null,
                'foto_profil' => $murid->foto_profil,
            ],
        ], 201);
    }

    // ==========================
    // LOGIN MURID
    // ==========================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required',
        ]);

        $murid = Murid::where('email', $request->email)->first();

        if (!$murid || !Hash::check($request->kata_sandi, $murid->kata_sandi)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau kata sandi salah!',
            ], 401);
        }

        // 🔐 BUAT TOKEN DI SINI (INI YANG KURANG)
        $token = $murid->createToken('murid-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil!',
            'token' => $token, // ✅ SEKARANG ADA
            'data' => [
                'id' => $murid->id,
                'nama' => $murid->nama,
                'email' => $murid->email,
                'kelas' => $murid->kelas ? $murid->kelas->nama_kelas : null,
                'nomer_whatsapp' => $murid->nomer_whatsapp,
                'jenis_kelamin' => $murid->jenis_kelamin,
                'foto_profil' => $murid->foto_profil,
            ],
        ], 200);
    }

    // ==========================
    // AMBIL DATA MURID BERDASARKAN EMAIL
    // ==========================
    public function getByEmail($email)
    {
        $decodedEmail = urldecode($email);
        $murid = Murid::where('email', $decodedEmail)->first();

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Data murid tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $murid
        ], 200);
    }

    // ==========================
    // TAMPILKAN PROFIL MURID BERDASARKAN ID
    // ==========================
    public function show($id)
    {
        $murid = Murid::find($id);

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Data murid tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $murid
        ], 200);
    }

    // ==========================
    // UPDATE PROFIL MURID BERDASARKAN ID (DENGAN FOTO)
    // ==========================
    public function update(Request $request, $id)
    {
        $murid = Murid::find($id);

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Data murid tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:murids,email,' . $murid->id,
            'kelas_id' => 'nullable|exists:kelas,id',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'nomer_whatsapp' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('foto_profil')) {
            if ($murid->foto_profil) {
                Storage::disk('public')->delete($murid->foto_profil);
            }
            $murid->foto_profil = $request->file('foto_profil')->store('foto_murid', 'public');
        }

        $murid->update([
            'nama' => $request->nama ?? $murid->nama,
            'email' => $request->email ?? $murid->email,
            'kelas_id' => $request->kelas_id ?? $murid->kelas_id,
            'nomer_whatsapp' => $request->nomer_whatsapp ?? $murid->nomer_whatsapp,
            'jenis_kelamin' => $request->jenis_kelamin ?? $murid->jenis_kelamin,
            'foto_profil' => $murid->foto_profil,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil murid berhasil diperbarui',
            'data' => $murid,
        ], 200);
    }

    // ==========================
    // UPDATE PROFIL MURID BERDASARKAN EMAIL (UNTUK FLUTTER)
    // ==========================
    public function updateByEmail(Request $request)
    {
        $murid = Murid::where('email', $request->email)->first();

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Data murid tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama' => 'nullable|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'nomer_whatsapp' => 'nullable|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:20480',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($murid->foto_profil) {
                Storage::disk('public')->delete($murid->foto_profil);
            }
            $murid->foto_profil = $request->file('foto_profil')->store('foto_murid', 'public');
        }

        $murid->update([
            'nama' => $request->nama ?? $murid->nama,
            'kelas_id' => $request->kelas_id ?? $murid->kelas_id,
            'jenis_kelamin' => $request->jenis_kelamin ?? $murid->jenis_kelamin,
            'nomer_whatsapp' => $request->nomer_whatsapp ?? $murid->nomer_whatsapp,
            'foto_profil' => $murid->foto_profil,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil murid berhasil diperbarui',
            'data' => $murid,
        ], 200);
    }

    // ==========================
    // GANTI KATA SANDI MURID
    // ==========================
public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi_lama' => 'required|string',
            'kata_sandi_baru' => 'required|string|min:6',
        ]);

        $murid = Murid::where('email', $request->email)->first();

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan'
            ], 404);
        }

        if (!Hash::check($request->kata_sandi_lama, $murid->kata_sandi)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi lama salah!'
            ], 400);
        }

        $murid->update([
            'kata_sandi' => Hash::make($request->kata_sandi_baru),
        ]);

        // 🔹 Buat notifikasi baru
        Notification::create([
            'murid_id' => $murid->id,
            'title' => 'Cihuy! Kata Sandi Berhasil Diubah',
            'message' => 'Demi keamanan akun, jika itu bukan anda silahkan hubungi wali kelas.',
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui'
        ], 200);
    }

    // ==========================
    // RESET DATA KELAS & MURID (TANPA ERROR FOREIGN KEY)
    // ==========================
    public function resetData()
    {
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Nonaktifkan FK

            Murid::truncate();
            Kelas::truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Aktifkan lagi FK

            return response()->json([
                'success' => true,
                'message' => 'Data kelas dan murid berhasil dihapus tanpa error foreign key.'
            ], 200);
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getNotifications($email)
    {
        $murid = Murid::where('email', $email)->first();

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Data murid tidak ditemukan',
            ], 404);
        }

        $notifications = $murid->notifications()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ], 200);
    }
}
