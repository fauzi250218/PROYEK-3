<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Guru;

class KontakController extends Controller
{
    public function getKontak($email)
    {
        $murid = Murid::with('kelas')
            ->where('email', $email)
            ->first();

        if (!$murid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Murid tidak ditemukan'
            ], 404);
        }

        $kelas = $murid->kelas;

        if (!$kelas) {
            return response()->json([
                'status' => 'error',
                'message' => 'Murid belum memiliki kelas'
            ], 404);
        }

        // ===============================
        // TEMAN SE-KELAS
        // ===============================
        $teman = $kelas->murids()
            ->where('id', '!=', $murid->id)
            ->get()
            ->map(function ($t) {
                return [
                    'id'          => $t->id, // murids.id
                    'nama'        => $t->nama,
                    'email'       => $t->email,
                    'role'        => 'murid',
                    'foto_profil' => $this->formatFoto($t->foto_profil),
                ];
            });

        // ===============================
        // 🔥 WALI KELAS (AMBIL LANGSUNG DARI TABEL GURU)
        // ===============================
        $wali = null;

        if ($kelas->guru_id) {
            $guru = Guru::with('user')
                ->where('id', $kelas->guru_id) // 🔒 PASTI guru.id
                ->first();

            if ($guru) {
                $wali = [
                    'id'          => $guru->id, // ✅ guru.id (FINAL)
                    'nama'        => $guru->user->name ?? '-',
                    'email'       => $guru->user->email ?? '-',
                    'role'        => 'guru',
                    'foto_profil' => $this->formatFoto(
                        $guru->foto_profil ?? $guru->user->foto_profil
                    ),
                ];
            }
        }

        return response()->json([
            'wali_kelas' => $wali,
            'teman'      => $teman
        ], 200);
    }

    // ===============================
    // HELPER FORMAT FOTO
    // ===============================
    private function formatFoto($foto)
    {
        if (!$foto) return null;

        if (str_starts_with($foto, 'http')) {
            return $foto;
        }

        return asset("storage/" . $foto);
    }
}
