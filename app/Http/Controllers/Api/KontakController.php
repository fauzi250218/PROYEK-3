<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Guru;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    public function getKontak($email)
    {
        $murid = Murid::with('kelas.guru.user')
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

        $teman = $kelas->murids()
            ->where('id', '!=', $murid->id)
            ->get()
            ->map(function ($t) {
                return [
                    'id'    => $t->id,
                    'nama'  => $t->nama,
                    'email' => $t->email,
                    'foto_profil' => $this->formatFoto($t->foto_profil)
                ];
            });

        $wali = null;
        if ($kelas->guru && $kelas->guru->user) {
            $guru = $kelas->guru->user;
            $wali = [
                'id'    => $guru->id,
                'nama'  => $guru->name,
                'email' => $guru->email,
                'role'  => 'guru',
                'foto_profil' => $this->formatFoto($guru->foto_profil)
            ];
        }

        return response()->json([
            'wali_kelas' => $wali,
            'teman'      => $teman
        ], 200);
    }


    // Tambahkan fungsi ini di dalam controller
    private function formatFoto($foto)
    {
        if (!$foto) return null;

        if (str_starts_with($foto, 'http')) {
            return $foto;
        }

        return asset("storage/" . $foto);
    }

}
