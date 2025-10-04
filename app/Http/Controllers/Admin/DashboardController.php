<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Murid; // ✅ import model Murid

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard admin
     */
    public function index()
    {
        // Hitung total guru
        $totalGuru = Guru::count();

        // Hitung total murid
        $totalMurid = Murid::count();

        // Hitung distribusi murid per kelas
        $muridKelas7 = Murid::where('kelas', '7')->count();
        $muridKelas8 = Murid::where('kelas', '8')->count();
        $muridKelas9 = Murid::where('kelas', '9')->count();

        // Kirim data ke view
        return view('admin.dashboard.index', compact(
            'totalGuru',
            'totalMurid',   // ✅ ikut dikirim ke blade
            'muridKelas7',
            'muridKelas8',
            'muridKelas9'
        ));
    }
}
