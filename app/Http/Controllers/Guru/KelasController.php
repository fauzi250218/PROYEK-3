<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class KelasController extends Controller
{
    public function index()
    {
        return view('guru.kelas.index');
    }
}

