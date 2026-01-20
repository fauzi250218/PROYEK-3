<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TagihanSPP;
use App\Models\Murid;
use Illuminate\Http\Request;

class TagihanSPPController extends Controller
{
    public function create()
    {
        $murid = Murid::with('kelas')->get();
        return view('admin.pembayaran.buat-tagihan', compact('murid'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'murid_id' => 'required',
            'bulan' => 'required',
            'tahun' => 'required',
            'nominal' => 'required|numeric'
        ]);

        TagihanSPP::create([
            'murid_id' => $request->murid_id,
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
            'nominal' => $request->nominal,
            'status' => 'belum_bayar'
        ]);

        return redirect()
            ->route('admin.pembayaran-spp.index')
            ->with('success', 'Tagihan SPP berhasil dibuat');
    }
}
