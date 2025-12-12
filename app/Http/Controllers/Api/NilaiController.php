<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Ambil semua nilai milik murid berdasarkan murid_id
     */
    public function getNilaiByMurid($murid_id)
    {
        try {
            $nilai = Nilai::where('murid_id', $murid_id)->get();

            return response()->json([
                'success' => true,
                'data' => $nilai
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }
}
