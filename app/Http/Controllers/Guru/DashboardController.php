<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard guru
     */
    public function index()
    {
        // Bisa ambil data khusus guru di sini
        return view('guru.dashboard.index', [
            'title' => 'Dashboard Guru',
        ]);
    }
}
