<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class KelasAjaranController extends Controller
{
    public function index()
    {
        $kelasAjaran = [
            ['kelas' => 'Kelas 7A', 'mapel' => 'Matematika'],
            ['kelas' => 'Kelas 8C', 'mapel' => 'IPA'],
            ['kelas' => 'Kelas 9B', 'mapel' => 'Bahasa Inggris'],
        ];

        return view('guru.kelas.ajaran.index', compact('kelasAjaran'));
    }
}
