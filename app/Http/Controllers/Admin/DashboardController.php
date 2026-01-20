<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Murid;
use App\Models\Kelas;
use App\Models\PembayaranSPP;
use App\Models\TagihanSPP;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /* ===============================
           DATA UTAMA
        =============================== */
        $totalGuru  = Guru::count();
        $totalMurid = Murid::count();
        $totalKelas = Kelas::count();

        /* ===============================
           DATA PEMBAYARAN SPP
        =============================== */

        // SPP LUNAS (jumlah murid unik)
        $sppLunas = TagihanSPP::where('status', 'lunas')
            ->distinct('murid_id')
            ->count('murid_id');

        // SPP BELUM LUNAS (jumlah murid unik)
        $sppBelumLunas = TagihanSPP::where('status', '!=', 'lunas')
            ->distinct('murid_id')
            ->count('murid_id');

        // Total nominal pembayaran sukses
        $totalPembayaran = PembayaranSPP::whereIn(
            'transaction_status',
            ['settlement', 'capture']
        )->sum('gross_amount');

        /* ===============================
           DATA BULAN
        =============================== */
        $bulan = collect(range(1, 12))
            ->map(fn($b) => Carbon::create()->month($b)->format('M'))
            ->toArray();

        /* ===============================
           DATA GRAFIK MURID
        =============================== */
        $muridKelas7PerBulan = [];
        $muridKelas8PerBulan = [];
        $muridKelas9PerBulan = [];

        foreach (range(1, 12) as $month) {

            $muridKelas7PerBulan[] = Murid::whereHas('kelas', function ($q) {
                $q->where('nama_kelas', 'like', '7%');
            })->whereMonth('created_at', $month)->count();

            $muridKelas8PerBulan[] = Murid::whereHas('kelas', function ($q) {
                $q->where('nama_kelas', 'like', '8%');
            })->whereMonth('created_at', $month)->count();

            $muridKelas9PerBulan[] = Murid::whereHas('kelas', function ($q) {
                $q->where('nama_kelas', 'like', '9%');
            })->whereMonth('created_at', $month)->count();
        }

        /* ===============================
           GURU BERDASARKAN GENDER
        =============================== */
        $guruLaki = Guru::where('jenis_kelamin', 'Laki-laki')->count();
        $guruPerempuan = Guru::where('jenis_kelamin', 'Perempuan')->count();

        /* ===============================
           KIRIM KE VIEW
        =============================== */
        return view('admin.dashboard.index', compact(
            'totalGuru',
            'totalMurid',
            'totalKelas',
            'sppLunas',
            'sppBelumLunas',
            'totalPembayaran',
            'bulan',
            'muridKelas7PerBulan',
            'muridKelas8PerBulan',
            'muridKelas9PerBulan',
            'guruLaki',
            'guruPerempuan'
        ));
    }
}
