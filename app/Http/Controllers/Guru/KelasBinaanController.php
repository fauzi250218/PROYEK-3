<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;

class KelasBinaanController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        if (!$guru) {
            return view('guru.manajemen-kelas.kelas-binaan.index', [
                'kelasBinaan' => collect(),
                'error' => 'Data guru tidak ditemukan atau belum diatur oleh admin.'
            ]);
        }

        $kelasBinaan = Kelas::with(['murids', 'guru.user'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.manajemen-kelas.kelas-binaan.index', compact('kelasBinaan'));
    }

    public function dataSiswa($id)
    {
        $guru = Guru::where('user_id', Auth::id())->first();

        if (!$guru) {
            abort(403, 'Akses ditolak: guru tidak ditemukan.');
        }

        $kelas = Kelas::with(['murids', 'guru.user'])
            ->where('guru_id', $guru->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('guru.manajemen-kelas.kelas-binaan.data_siswa', compact('kelas'));
    }
}
