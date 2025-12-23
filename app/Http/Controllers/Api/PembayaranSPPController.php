<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TagihanSPP;
use App\Models\PembayaranSPP;
use Midtrans\Snap;
use Midtrans\Config;

class PembayaranSPPController extends Controller
{
    /**
     * Buat transaksi pembayaran SPP (Midtrans Snap)
     */
    public function create(Request $request)
    {
        $request->validate([
            'tagihan_id' => 'required|exists:tagihan_spp,id',
        ]);

        $tagihan = TagihanSPP::findOrFail($request->tagihan_id);

        if ($tagihan->status === 'LUNAS') {
            return response()->json([
                'message' => 'Tagihan sudah lunas'
            ], 400);
        }

        // ================= MIDTRANS CONFIG =================
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized  = config('midtrans.is_sanitized', true);
        Config::$is3ds        = config('midtrans.is_3ds', true);

        $orderId = 'SPP-' . $tagihan->id . '-' . time();

        // ================= SIMPAN KE DB =================
        $pembayaran = PembayaranSPP::create([
            'tagihan_id'         => $tagihan->id,
            'order_id'           => $orderId,
            'nominal'            => $tagihan->nominal,
            'gross_amount'       => $tagihan->nominal,
            'transaction_status' => 'pending',
        ]);

        // ================= PARAMS MIDTRANS =================
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $tagihan->nominal,
            ],
            'customer_details' => [
                'first_name' => $request->user()->nama ?? 'Murid',
                'email'      => $request->user()->email,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // 🔥 SIMPAN SNAP TOKEN
        $pembayaran->update([
            'snap_token' => $snapToken
        ]);

        return response()->json([
            'snap_token' => $snapToken
        ]);
    }

    /**
     * 🔥 RIWAYAT PEMBAYARAN MURID
     */
    public function history(Request $request)
    {
        // ⬇️ PENTING: user Sanctum = Murid
        $murid = $request->user();

        if (!$murid) {
            return response()->json([], 200);
        }

        $riwayat = PembayaranSPP::whereHas('tagihan', function ($q) use ($murid) {
                $q->where('murid_id', $murid->id); // ✅ FIX FINAL
            })
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'order_id',
                'nominal',
                'gross_amount',
                'transaction_status',
                'payment_type',
                'updated_at'
            ]);

        return response()->json($riwayat, 200);
    }

    // public function history(Request $request)
    // {
    //     // 1️⃣ user dari Sanctum
    //     $user = $request->user();

    //     if (!$user || !$user->murid) {
    //         return response()->json([], 200);
    //     }

    //     // 2️⃣ ambil murid_id yang BENAR
    //     $muridId = $user->murid->id;

    //     // 3️⃣ filter lewat relasi tagihan
    //     $riwayat = PembayaranSPP::whereHas('tagihan', function ($q) use ($muridId) {
    //             $q->where('murid_id', $muridId);
    //         })
    //         ->orderBy('created_at', 'desc')
    //         ->get([
    //             'id',
    //             'order_id',
    //             'nominal',
    //             'gross_amount',
    //             'transaction_status',
    //             'payment_type',
    //             'updated_at'
    //         ]);

    //     return response()->json($riwayat, 200);
    // }

}
