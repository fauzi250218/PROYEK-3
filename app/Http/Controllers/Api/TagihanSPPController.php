<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanSPP;

class TagihanSPPController extends Controller
{
    public function index(Request $request)
    {
        // 🔐 Murid login langsung (Sanctum)
        $murid = $request->user();

        if (!$murid) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // Ambil tagihan milik murid
        $tagihan = TagihanSPP::where('murid_id', $murid->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get([
                'id',
                'bulan',
                'tahun',
                'nominal',
                'status'
            ]);

        return response()->json($tagihan, 200);
    }
}
