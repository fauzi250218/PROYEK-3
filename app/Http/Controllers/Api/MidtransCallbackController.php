<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PembayaranSPP;
use App\Models\TagihanSPP;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        Log::info('MIDTRANS CALLBACK MASUK', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId || !$transactionStatus) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // ===============================
        // VALIDASI SIGNATURE KEY
        // ===============================
        $serverKey = config('midtrans.server_key');

        $expectedSignature = hash(
            'sha512',
            $orderId .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $serverKey
        );

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Signature tidak valid', [
                'expected' => $expectedSignature,
                'received' => $signatureKey
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // ===============================
        // CARI PEMBAYARAN
        // ===============================
        $pembayaran = PembayaranSPP::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // ===============================
        // UPDATE PEMBAYARAN
        // ===============================
        $pembayaran->update([
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
        ]);

        // ===============================
        // JIKA SUKSES → TAGIHAN LUNAS
        // ===============================
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            TagihanSPP::where('id', $pembayaran->tagihan_id)
                ->update(['status' => 'LUNAS']);
        }

        return response()->json(['message' => 'Callback processed']);
    }
}
