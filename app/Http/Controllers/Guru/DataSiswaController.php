<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class DataSiswaController extends Controller
{
    public function index()
    {
        // Ambil nama guru login
        $guruNama = Auth::user()->nama;

        // Cari kelas binaan guru (di mana dia jadi wali_kelas)
        $kelas = Kelas::with('murids')
            ->where('wali_kelas', $guruNama)
            ->first();

        // Kirim ke view
        return view('guru.manajemen-siswa.data-siswa', compact('kelas'));
    }
}
