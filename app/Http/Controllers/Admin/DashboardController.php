<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Murid;
use App\Models\Kelas;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGuru = Guru::count();
        $totalMurid = Murid::count();
        $totalKelas = Kelas::count();

        $muridKelas7 = Murid::whereHas('kelas', function ($q) {
            $q->where('nama_kelas', 'like', '%7%');
        })->count();

        $muridKelas8 = Murid::whereHas('kelas', function ($q) {
            $q->where('nama_kelas', 'like', '%8%');
        })->count();

        $muridKelas9 = Murid::whereHas('kelas', function ($q) {
            $q->where('nama_kelas', 'like', '%9%');
        })->count();

        $kelasData = Kelas::withCount('murids')->get();

        return view('admin.dashboard.index', compact(
            'totalGuru',
            'totalMurid',
            'totalKelas',
            'muridKelas7',
            'muridKelas8',
            'muridKelas9',
            'kelasData'
        ));
    }
}
