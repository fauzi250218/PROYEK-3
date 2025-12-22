<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Jadwal;
use App\Models\Modul;
use Illuminate\Http\Request;

class ModulController extends Controller
{
    /**
     * ==========================================
     * 1️⃣ GET daftar mata pelajaran (DISTINCT)
     *    + Kembalikan jadwal_id yang punya MODUL
     * ==========================================
     */
    public function getMataPelajaran($murid_id)
    {
        $murid = Murid::find($murid_id);

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Murid tidak ditemukan'
            ], 404);
        }

        // Ambil daftar mapel unik
        $mapelList = Jadwal::where('kelas_id', $murid->kelas_id)
            ->select('mata_pelajaran')
            ->distinct()
            ->get();

        $result = $mapelList->map(function ($item) use ($murid) {

            $jadwalIds = Jadwal::where('kelas_id', $murid->kelas_id)
                ->where('mata_pelajaran', $item->mata_pelajaran)
                ->pluck('id');

            // Ambil semua jadwal_id yang punya modul
            $jadwalIdsModul = Modul::whereIn('jadwal_id', $jadwalIds)
                ->pluck('jadwal_id')
                ->unique()
                ->values()
                ->toArray();

            return [
                'mata_pelajaran' => $item->mata_pelajaran,
                'jadwal_ids' => $jadwalIdsModul
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * ==========================================
     * 2️⃣ GET modul berdasarkan JADWAL (id)
     *    Diambil dari tabel moduls
     * ==========================================
     */
    public function getModulByMapelJadwal($jadwal_id)
    {
        if (!$jadwal_id || $jadwal_id == "null") {
            return response()->json([
                'success' => false,
                'message' => 'jadwal_id tidak valid'
            ], 400);
        }

        $modul = Modul::where('jadwal_id', $jadwal_id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'kelas_id' => $m->kelas_id,
                    'sesi_id' => $m->sesi_id,
                    'jadwal_id' => $m->jadwal_id,
                    'judul' => $m->judul,
                    'file' => $m->file,
                    'topik' => $m->topik,
                    'catatan' => $m->catatan,
                    'created_at' => $m->created_at,
                ];
            });

        if ($modul->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada modul untuk mata pelajaran ini'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $modul
        ]);
    }

    public function getModulByManyJadwal(Request $req)
    {
        $jadwalIds = $req->jadwal_ids;

        if (!$jadwalIds || !is_array($jadwalIds)) {
            return response()->json([
                'success' => false,
                'message' => 'jadwal_ids tidak valid'
            ], 400);
        }

        $modul = Modul::whereIn('jadwal_id', $jadwalIds)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($m) {
                return [
                    'id' => $m->id,
                    'kelas_id' => $m->kelas_id,
                    'sesi_id' => $m->sesi_id,
                    'jadwal_id' => $m->jadwal_id,
                    'judul' => $m->judul,
                    'file' => $m->file
                    ? url(Storage::url($m->file))
                    : null,
                    'topik' => $m->topik,
                    'catatan' => $m->catatan,
                    'created_at' => $m->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $modul
        ]);
    }

}
