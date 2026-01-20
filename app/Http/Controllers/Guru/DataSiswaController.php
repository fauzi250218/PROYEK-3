<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

class DataSiswaController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        if (!$guru) {
            return back()->with('error', 'Data guru belum diatur oleh admin.');
        }

        $kelas = Kelas::with('murids')
            ->where('guru_id', $guru->id)
            ->first();

        return view('guru.manajemen-siswa.data-siswa', compact('kelas'));
    }
}
