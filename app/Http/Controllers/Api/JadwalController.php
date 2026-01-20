<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // 🔹 Ambil semua jadwal berdasarkan kelas_id
    public function index(Request $request)
    {
        $kelasId = $request->kelas_id;

        if (!$kelasId) {
            return response()->json(['message' => 'kelas_id diperlukan'], 400);
        }

        $jadwals = Jadwal::where('kelas_id', $kelasId)
            ->orderBy('tanggal', 'asc')
            ->get();

        return response()->json($jadwals);
    }

    // 🔹 Ambil jadwal berdasarkan tanggal tertentu (opsional)
    public function getByDate(Request $request)
    {
        $kelasId = $request->kelas_id;
        $tanggal = $request->tanggal;

        $jadwals = Jadwal::where('kelas_id', $kelasId)
            ->whereDate('tanggal', $tanggal)
            ->get();

        return response()->json($jadwals);
    }
}
