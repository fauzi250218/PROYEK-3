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

        return view(
            'guru.manajemen-kelas.kelas-binaan.index',
            compact('kelasBinaan')
        );
    }

    public function dataSiswa($id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $kelas = Kelas::with(['murids', 'guru.user'])
            ->where('id', $id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        return view(
            'guru.manajemen-kelas.kelas-binaan.data_siswa',
            compact('kelas')
        );
    }

    public function perkembangan($id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $kelas = Kelas::with(['murids.nilai'])
            ->where('id', $id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        return view(
            'guru.manajemen-kelas.kelas-binaan.perkembangan',
            compact('kelas')
        );
    }

    /**
     * 🔥 KEHADIRAN (FIXED)
     * - eager load murids + kehadiran
     * - aman untuk PHP 8
     */
    public function kehadiran($id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $kelas = Kelas::with([
            'murids',
            'murids.kehadiran'
        ])
            ->where('id', $id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        return view(
            'guru.manajemen-kelas.kelas-binaan.kehadiran',
            compact('kelas')
        );
    }

    public function catatan($id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $kelas = Kelas::with(['murids.catatan'])
            ->where('id', $id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        return view(
            'guru.manajemen-kelas.kelas-binaan.catatan',
            compact('kelas')
        );
    }

    public function laporan($id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $kelas = Kelas::with([
            'murids.nilai',
            'murids.kehadiran'
        ])
            ->where('id', $id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        return view(
            'guru.manajemen-kelas.kelas-binaan.laporan',
            compact('kelas')
        );
    }
}
