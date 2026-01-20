<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Menampilkan semua data kelas
     */
    public function index()
    {
        $kelas = Kelas::select('id', 'nama_kelas')->get();

        return response()->json([
            'success' => true,
            'data' => $kelas
        ]);
    }
}
