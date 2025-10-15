<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy untuk dicoba
        $jumlahMuridKelas = 32;
        $jumlahPerkembangan = 10;
        $jumlahNilai = 25;

        return view('guru.dashboard.index', compact(
            'jumlahMuridKelas',
            'jumlahPerkembangan',
            'jumlahNilai'
        ));
    }
}

