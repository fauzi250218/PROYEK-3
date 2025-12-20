<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagihanSPP;
use App\Models\PembayaranSPP;
use App\Models\Murid;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranSPPController extends Controller
{
    /* =====================================================
     | DAFTAR TAGIHAN
     ===================================================== */
    public function index()
    {
        $tagihan = TagihanSPP::with('murid.kelas')
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->get();

        return view('admin.pembayaran.daftar-tagihan', compact('tagihan'));
    }

    /* =====================================================
     | FORM BUAT TAGIHAN
     ===================================================== */
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.pembayaran.buat-tagihan', compact('kelas'));
    }

    /* =====================================================
     | SIMPAN TAGIHAN MASSAL
     ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'bulan'    => 'required',
            'tahun'    => 'required|numeric',
            'nominal'  => 'required|numeric|min:0',
        ]);

        $murids = $request->kelas_id === 'all'
            ? Murid::whereNotNull('kelas_id')->get()
            : Murid::where('kelas_id', $request->kelas_id)->get();

        foreach ($murids as $murid) {

            $exists = TagihanSPP::where('murid_id', $murid->id)
                ->where('bulan', $request->bulan)
                ->where('tahun', $request->tahun)
                ->exists();

            if ($exists) continue;

            TagihanSPP::create([
                'murid_id' => $murid->id,
                'bulan'    => $request->bulan,
                'tahun'    => $request->tahun,
                'nominal'  => $request->nominal,
                'status'   => 'belum_bayar',
            ]);
        }

        return redirect()
            ->route('admin.pembayaran-spp.index')
            ->with('success', 'Tagihan berhasil dibuat');
    }

    /* =====================================================
     | HALAMAN BAYAR (MIDTRANS SNAP)
     ===================================================== */
    public function bayar($id)
    {
        $tagihan = TagihanSPP::with('murid')->findOrFail($id);

        if ($tagihan->status === 'lunas') {
            return redirect()->route('admin.pembayaran-spp.index');
        }

        // MIDTRANS CONFIG
        Config::$serverKey    = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // CEK TRANSAKSI AKTIF
        $pembayaran = PembayaranSPP::where('tagihan_id', $tagihan->id)
            ->where('transaction_status', 'pending')
            ->first();

        if (!$pembayaran) {

            $orderId = 'SPP-' . $tagihan->id . '-' . Str::uuid();

            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $tagihan->nominal,
                ],
                'customer_details' => [
                    'first_name' => $tagihan->murid->nama,
                    'email'      => $tagihan->murid->email ?? 'no-reply@spp.local',
                ],
            ]);

            $pembayaran = PembayaranSPP::create([
                'tagihan_id'         => $tagihan->id,
                'order_id'           => $orderId,
                'gross_amount'       => $tagihan->nominal,
                'transaction_status' => 'pending',
                'snap_token'         => $snapToken,
            ]);
        }

        return view('admin.pembayaran.bayar-spp', [
            'tagihan'   => $tagihan,
            'snapToken' => $pembayaran->snap_token
        ]);
    }

    /* =====================================================
     | FORCE LUNAS (LOCALHOST / DEMO)
     ===================================================== */
    public function forceLunas($id)
    {
        DB::transaction(function () use ($id) {

            $tagihan = TagihanSPP::findOrFail($id);

            PembayaranSPP::where('tagihan_id', $id)
                ->where('transaction_status', 'pending')
                ->update([
                    'transaction_status' => 'settlement',
                    'payment_type'       => 'midtrans'
                ]);

            $tagihan->update([
                'status' => 'lunas'
            ]);
        });

        return response()->json(['status' => 'lunas']);
    }

    /* =====================================================
     | CALLBACK MIDTRANS (PRODUCTION ONLY)
     ===================================================== */
    public function callback(Request $request)
    {
        $signature = hash(
            'sha512',
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                config('midtrans.serverKey')
        );

        if ($signature !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = PembayaranSPP::where('order_id', $request->order_id)
            ->firstOrFail();

        $pembayaran->update([
            'payment_type'       => $request->payment_type,
            'transaction_status' => $request->transaction_status,
        ]);

        if (in_array($request->transaction_status, ['capture', 'settlement'])) {
            $pembayaran->tagihan->update([
                'status' => 'lunas'
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
