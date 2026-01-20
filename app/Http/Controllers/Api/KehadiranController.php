<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Murid;
use App\Models\Jadwal;
use App\Models\Kehadiran;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    /**
     * ==========================================
     * 1️⃣ GET daftar mata pelajaran (distinct)
     *    → untuk list awal di Flutter
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

        $mapelList = Jadwal::where('kelas_id', $murid->kelas_id)
            ->select('mata_pelajaran')
            ->distinct()
            ->get();

        $result = $mapelList->map(function ($item) use ($murid) {

            $jadwalIds = Jadwal::where('kelas_id', $murid->kelas_id)
                ->where('mata_pelajaran', $item->mata_pelajaran)
                ->pluck('id');

            return [
                'mata_pelajaran' => $item->mata_pelajaran,
                'jadwal_ids' => $jadwalIds
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * ==========================================
     * 2️⃣ GET rincian kehadiran siswa
     *    → dipakai di rinciankehadiran_screen
     * ==========================================
     */
    public function getKehadiranByManyJadwal(Request $request)
    {
        $request->validate([
            'murid_id' => 'required|integer',
            'jadwal_ids' => 'required|array',
        ]);

        $kehadiran = Kehadiran::with(['jadwal', 'sesi'])
            ->where('murid_id', $request->murid_id)
            ->whereIn('jadwal_id', $request->jadwal_ids)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'sesi_id' => $k->sesi_id,
                    'jadwal_id' => $k->jadwal_id,
                    'mata_pelajaran' => $k->jadwal->mata_pelajaran ?? null,
                    'status' => $k->status,
                    'tanggal' => $k->created_at->format('Y-m-d H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $kehadiran
        ]);
    }
}

// namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
// use App\Models\Kehadiran;
// use App\Models\Murid;
// use App\Models\Jadwal;
// use Illuminate\Http\Request;
// use Carbon\Carbon;

// class KehadiranController extends Controller
// {
//     /**
//      * ====================================================
//      * 1️⃣ GET MATA PELAJARAN KEHADIRAN (DISTINCT)
//      * ====================================================
//      */
//     public function getMataPelajaran($murid_id)
//     {
//         $murid = Murid::find($murid_id);
//         if (!$murid) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Murid tidak ditemukan'
//             ], 404);
//         }

//         $mapelList = Jadwal::where('kelas_id', $murid->kelas_id)
//             ->select('mata_pelajaran')
//             ->distinct()
//             ->get();

//         $result = $mapelList->map(function ($item) use ($murid) {
//             $jadwalIds = Jadwal::where('kelas_id', $murid->kelas_id)
//                 ->where('mata_pelajaran', $item->mata_pelajaran)
//                 ->pluck('id');

//             return [
//                 'mata_pelajaran' => $item->mata_pelajaran,
//                 'jadwal_ids' => $jadwalIds->values()
//             ];
//         });

//         return response()->json([
//             'success' => true,
//             'data' => $result
//         ]);
//     }

//     /**
//      * ====================================================
//      * 2️⃣ GET DETAIL KEHADIRAN BY MANY JADWAL
//      * ====================================================
//      */
//     public function getKehadiranByManyJadwal(Request $request)
//     {
//         $request->validate([
//             'murid_id'   => 'required|integer',
//             'jadwal_ids' => 'required|array'
//         ]);

//         $data = Kehadiran::where('murid_id', $request->murid_id)
//             ->whereIn('jadwal_id', $request->jadwal_ids)
//             ->orderBy('sesi_id', 'asc')
//             ->get()
//             ->map(function ($k) {
//                 return [
//                     'id'             => $k->id,
//                     'sesi_id'        => $k->sesi_id,
//                     'jadwal_id'      => $k->jadwal_id,
//                     'mata_pelajaran' => $k->jadwal->mata_pelajaran ?? '-',
//                     'status'         => $k->status,
//                     'tanggal'        => Carbon::parse($k->created_at)->format('Y-m-d H:i'),
//                 ];
//             });

//         return response()->json([
//             'success' => true,
//             'data'    => $data
//         ]);
//     }

//     /**
//      * ====================================================
//      * 3️⃣ SIMPAN ABSENSI (INSERT, BUKAN UPDATE)
//      * ====================================================
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'murid_id'  => 'required|integer',
//             'jadwal_id' => 'required|integer',
//             'sesi_id'   => 'required|integer',
//             'status'    => 'required|in:H,S,I,A',
//         ]);

//         // ❗ CEGAH DUPLIKAT DALAM SESI YANG SAMA
//         $exists = Kehadiran::where([
//             'murid_id'  => $request->murid_id,
//             'jadwal_id' => $request->jadwal_id,
//             'sesi_id'   => $request->sesi_id,
//         ])->exists();

//         if ($exists) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'Absensi sudah tercatat untuk sesi ini'
//             ], 409);
//         }

//         $kehadiran = Kehadiran::create([
//             'murid_id'  => $request->murid_id,
//             'jadwal_id' => $request->jadwal_id,
//             'sesi_id'   => $request->sesi_id,
//             'status'    => $request->status,
//         ]);

//         return response()->json([
//             'success' => true,
//             'message' => 'Absensi berhasil disimpan',
//             'data'    => $kehadiran
//         ]);
//     }
// }
