<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Nilai;
use App\Models\CatatanPerkembangan;

class PerkembanganController extends Controller
{
    // ============================
    // 1. LIST PERKEMBANGAN PER KELAS
    // ============================
    public function index($kelas_id)
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        if (!$guru) return back()->with('error', 'Data guru belum diatur.');

        $kelas = Kelas::with('murids')
            ->where('id', $kelas_id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        // hitung rata rata
        $murids = $kelas->murids()->get()->map(function ($murid) {
            $avg = $murid->nilai()->avg('rata_rata');
            $murid->rata_rata = $avg ? round($avg, 2) : 0.0;
            return $murid;
        });

        // ranking
        $sorted = $murids->sortByDesc('rata_rata')->values();
        foreach ($sorted as $i => $m) {
            $m->ranking = $i + 1;
        }

        return view('guru.manajemen-kelas.kelas-binaan.perkembangan.index', [
            'guru' => $guru,
            'kelas' => $kelas,
            'murids' => $sorted,
        ]);
    }

    // ============================
    // 2. DETAIL PER MURID
    // ============================
    public function show($kelas_id, $murid_id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $kelas = Kelas::where('id', $kelas_id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $murid = Murid::with('kelas')->where('id', $murid_id)->firstOrFail();

        if ($murid->kelas_id !== $kelas->id) {
            abort(403, 'Murid tidak berada di kelas ini.');
        }

        $avg = $murid->nilai()->avg('rata_rata');
        $murid->rata_rata = $avg ? round($avg, 2) : 0.0;

        $catatan = $murid->catatanPerkembangan()
            ->with('guru')
            ->orderByDesc('created_at')
            ->get();

        return view('guru.manajemen-kelas.kelas-binaan.perkembangan.show', [
            'guru' => $guru,
            'murid' => $murid,
            'kelas' => $kelas,
            'catatan' => $catatan
        ]);
    }

    // ============================
    // 3. SIMPAN CATATAN
    // ============================
    public function storeCatatan(Request $request, $kelas_id, $murid_id)
    {
        $request->validate([
            'kategori' => 'required|string|max:100',
            'catatan' => 'required|string',
            'tanggal' => 'nullable|date',
        ]);

        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $kelas = Kelas::where('id', $kelas_id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $murid = Murid::where('id', $murid_id)
            ->where('kelas_id', $kelas->id)
            ->firstOrFail();

        CatatanPerkembangan::create([
            'murid_id' => $murid->id,
            'guru_id' => $guru->id,
            'kategori' => $request->kategori,
            'catatan' => $request->catatan,
            'tanggal' => $request->tanggal ?? now()->format('Y-m-d'),
        ]);

        return back()->with('success', 'Catatan tersimpan.');
    }

    // ============================
    // 4. HAPUS CATATAN
    // ============================
    public function destroyCatatan($kelas_id, $catatan_id)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $catatan = CatatanPerkembangan::where('id', $catatan_id)
            ->where('guru_id', $guru->id)
            ->firstOrFail();

        $catatan->delete();

        return back()->with('success', 'Catatan dihapus.');
    }
}
