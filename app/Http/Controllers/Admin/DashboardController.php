<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Murid;
use App\Models\Kelas;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ===============================
        // 📊 DATA UTAMA
        // ===============================
        $totalGuru  = Guru::count();
        $totalMurid = Murid::count();
        $totalKelas = Kelas::count();

        // ===============================
        // 📅 DATA MURID PER KELAS (7, 8, 9)
        // ===============================
        $muridKelas7 = Murid::whereHas('kelas', fn($q) => $q->where('nama_kelas', 'like', '7%'))->count();
        $muridKelas8 = Murid::whereHas('kelas', fn($q) => $q->where('nama_kelas', 'like', '8%'))->count();
        $muridKelas9 = Murid::whereHas('kelas', fn($q) => $q->where('nama_kelas', 'like', '9%'))->count();

        // ===============================
        // 📈 DATA GRAFIK MURID PER BULAN
        // ===============================
        $bulan = collect(range(1, 12))->map(fn($b) => Carbon::create()->month($b)->format('M'));

        $muridKelas7PerBulan = [];
        $muridKelas8PerBulan = [];
        $muridKelas9PerBulan = [];

        foreach (range(1, 12) as $month) {
            $muridKelas7PerBulan[] = Murid::whereHas('kelas', fn($q) =>
                $q->where('nama_kelas', 'like', '7%')
            )->whereMonth('created_at', $month)->count();

            $muridKelas8PerBulan[] = Murid::whereHas('kelas', fn($q) =>
                $q->where('nama_kelas', 'like', '8%')
            )->whereMonth('created_at', $month)->count();

            $muridKelas9PerBulan[] = Murid::whereHas('kelas', fn($q) =>
                $q->where('nama_kelas', 'like', '9%')
            )->whereMonth('created_at', $month)->count();
        }

        // ===============================
        // 👩‍🏫 DATA GRAFIK GURU BERDASARKAN GENDER
        // ===============================
        $guruLaki      = Guru::where('jenis_kelamin', 'Laki-laki')->count();
        $guruPerempuan = Guru::where('jenis_kelamin', 'Perempuan')->count();

        // ===============================
        // 📘 DATA KELAS (opsional untuk tabel bawah)
        // ===============================
        $kelasData = Kelas::withCount('murids')->get();

        // ===============================
        // 🔁 KIRIM KE VIEW
        // ===============================
        return view('admin.dashboard.index', compact(
            'totalGuru',
            'totalMurid',
            'totalKelas',
            'muridKelas7',
            'muridKelas8',
            'muridKelas9',
            'muridKelas7PerBulan',
            'muridKelas8PerBulan',
            'muridKelas9PerBulan',
            'guruLaki',
            'guruPerempuan',
            'kelasData',
            'bulan'
        ));
    }
}
