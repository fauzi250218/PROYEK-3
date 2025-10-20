<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Murid;
use App\Models\Perkembangan;
use App\Models\Nilai;
use App\Models\Kelas;

class GuruDashboardController extends Controller
{
    public function index()
    {
        // Pastikan guru terautentikasi
        $guru = Auth::user()->guru ?? null;

        if (!$guru) {
            abort(403, 'Akses ditolak: Guru tidak ditemukan.');
        }

        // Ambil kelas binaan guru (kalau ada)
        $kelas = Kelas::where('guru_id', $guru->id)->first();

        // Hitung total murid di kelas binaan
        $jumlahMuridKelas = $kelas ? $kelas->murids()->count() : 0;

        // Hitung total laporan perkembangan yang dibuat guru
        $jumlahPerkembangan = Perkembangan::where('guru_id', $guru->id)->count();

        // Hitung total nilai yang sudah diinput oleh guru
        $jumlahNilai = Nilai::where('guru_id', $guru->id)->count();

        // Grafik jumlah laporan perkembangan per bulan (1–12)
        $laporanPerBulan = Perkembangan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->where('guru_id', $guru->id)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Label bulan (Jan - Des)
        $labelsBulan = [];
        $dataBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $labelsBulan[] = date('F', mktime(0, 0, 0, $i, 1)); // Nama bulan dalam bahasa Inggris
            $dataBulan[] = $laporanPerBulan[$i] ?? 0;           // Jika kosong isi 0
        }

        // Kirim semua data ke view
        return view('guru.dashboard.index', [
            'jumlahMuridKelas' => $jumlahMuridKelas,
            'jumlahPerkembangan' => $jumlahPerkembangan,
            'jumlahNilai' => $jumlahNilai,
            'labelsBulan' => $labelsBulan,
            'dataBulan' => $dataBulan,
        ]);
    }
}
