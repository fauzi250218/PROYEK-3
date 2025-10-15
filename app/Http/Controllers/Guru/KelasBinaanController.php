<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class KelasBinaanController extends Controller
{
    /**
     * 🔹 Tampilkan semua kelas yang dibina oleh guru yang sedang login.
     */
    public function index()
    {
        // Ambil ID user (guru) yang sedang login
        $userId = Auth::id();

        // Ambil semua kelas di mana user_id = id guru login
        $kelasBinaan = Kelas::with('murids', 'wali')
            ->where('user_id', $userId)
            ->get();

        // Jika tidak ada kelas binaan
        if ($kelasBinaan->isEmpty()) {
            return view('guru.manajemen-kelas.kelas-binaan.index', [
                'kelasBinaan' => collect(), // biar tidak error di view
                'error' => 'Anda belum memiliki kelas binaan atau data belum diatur oleh admin.'
            ]);
        }

        return view('guru.manajemen-kelas.kelas-binaan.index', compact('kelasBinaan'));
    }

    /**
     * 🔹 Menampilkan daftar siswa dalam kelas binaan.
     */
    public function dataSiswa($id)
    {
        $kelas = $this->getKelasBinaan($id);

        // Muat juga relasi murid dan wali agar tampil di view
        $kelas->load(['murids', 'wali']);

        return view('guru.manajemen-kelas.kelas-binaan.data_siswa', compact('kelas'));
    }

    /**
     * 🔹 Menampilkan halaman perkembangan siswa di kelas binaan.
     */
    public function perkembangan($id)
    {
        $kelas = $this->getKelasBinaan($id);
        return view('guru.manajemen-kelas.kelas-binaan.perkembangan', compact('kelas'));
    }

    /**
     * 🔹 Menampilkan halaman rekap kehadiran siswa.
     */
    public function kehadiran($id)
    {
        $kelas = $this->getKelasBinaan($id);
        return view('guru.manajemen-kelas.kelas-binaan.kehadiran', compact('kelas'));
    }

    /**
     * 🔹 Menampilkan catatan perilaku siswa.
     */
    public function catatan($id)
    {
        $kelas = $this->getKelasBinaan($id);
        return view('guru.manajemen-kelas.kelas-binaan.catatan', compact('kelas'));
    }

    /**
     * 🔹 Menampilkan laporan akhir kelas.
     */
    public function laporan($id)
    {
        $kelas = $this->getKelasBinaan($id);
        return view('guru.manajemen-kelas.kelas-binaan.laporan', compact('kelas'));
    }

    /**
     * 🧩 Helper
     * Pastikan kelas yang diakses memang milik guru yang sedang login.
     * Kalau tidak, akan lempar error 403.
     */
    private function getKelasBinaan($id)
    {
        $kelas = Kelas::with('murids')->findOrFail($id);

        if ($kelas->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }

        return $kelas;
    }
}
