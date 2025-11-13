<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Nilai;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * 1️⃣ Tampilkan semua kelas (file: semua-kelas.blade.php)
     */
    public function semuaKelas()
    {
        $kelasList = Kelas::with(['guru.user'])->get();
        return view('guru.nilai.semua-kelas', compact('kelasList'));
    }

    /**
     * 2️⃣ Tampilkan daftar mata pelajaran pada kelas yang dipilih (file: mapel.blade.php)
     */
    public function index($id)
    {
        $kelas = Kelas::with(['guru.user'])->findOrFail($id);

        // Ambil daftar mapel dari jadwal kelas ini
        $mapelList = Jadwal::where('kelas_id', $id)
            ->pluck('mata_pelajaran')
            ->unique();

        return view('guru.nilai.mapel', compact('kelas', 'mapelList'));
    }

    /**
     * 3️⃣ Tampilkan daftar murid berdasarkan kelas dan mapel yang dipilih (file: murid-mapel.blade.php)
     */
    public function muridPerMapel($kelasId, $mapel)
    {
        $kelas = Kelas::with('murids')->findOrFail($kelasId);
        $murids = $kelas->murids;
        $mataPelajaran = $mapel;

        return view('guru.nilai.murid-mapel', compact('kelas', 'murids', 'mataPelajaran'));
    }

    /**
     * 4️⃣ Tampilkan detail nilai untuk satu murid (file: detail.blade.php)
     */
    public function detail($id)
    {
        $murid = Murid::with(['kelas', 'nilai'])->findOrFail($id);
        $nilaiList = Nilai::where('murid_id', $id)->get();
        return view('guru.nilai.detail', compact('murid', 'nilaiList'));
    }

    /**
     * 5️⃣ Form tambah nilai (file: create.blade.php)
     */
    public function create($id)
    {
        $murid = Murid::with('kelas')->findOrFail($id);
        $guru = Auth::user()->guru;

        // Ambil mapel dari jadwal kelas tempat murid berada
        $mapelList = Jadwal::where('kelas_id', $murid->kelas_id)
            ->pluck('mata_pelajaran')
            ->unique();

        return view('guru.nilai.create', compact('murid', 'mapelList'));
    }

    /**
     * Simpan nilai baru (tugas, ulangan harian, uts, uas sekaligus)
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'mata_pelajaran'   => 'required|string|max:100',
            'tugas'            => 'nullable|numeric|min:0|max:100',
            'ulangan_harian'   => 'nullable|numeric|min:0|max:100',
            'uts'              => 'nullable|numeric|min:0|max:100',
            'uas'              => 'nullable|numeric|min:0|max:100',
        ]);

        $murid = Murid::findOrFail($id);
        $guruId = Auth::user()->guru->id ?? null;

        // Hitung rata-rata dari nilai yang diisi
        $nilaiArray = array_filter([
            $request->tugas,
            $request->ulangan_harian,
            $request->uts,
            $request->uas
        ], fn($v) => $v !== null);

        $rataRata = count($nilaiArray) > 0 ? array_sum($nilaiArray) / count($nilaiArray) : null;

        // Simpan data nilai
        Nilai::create([
            'murid_id'         => $murid->id,
            'guru_id'          => $guruId,
            'kelas_id'         => $murid->kelas_id,
            'mata_pelajaran'   => $request->mata_pelajaran,
            'tugas'            => $request->tugas,
            'ulangan_harian'   => $request->ulangan_harian,
            'uts'              => $request->uts,
            'uas'              => $request->uas,
            'rata_rata'        => $rataRata,
        ]);

        return redirect()->route('guru.nilai.detail', $murid->id)
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * 6️⃣ Form edit nilai (file: edit.blade.php)
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $guru = Auth::user()->guru;

        // Ambil daftar mapel dari jadwal kelas terkait
        $mapelList = Jadwal::where('kelas_id', $nilai->kelas_id)
            ->pluck('mata_pelajaran')
            ->unique();

        return view('guru.nilai.edit', compact('nilai', 'mapelList'));
    }

    /**
     * 7️⃣ Update nilai dan hitung ulang rata-rata
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'mata_pelajaran'   => 'required|string|max:100',
            'tugas'            => 'nullable|numeric|min:0|max:100',
            'ulangan_harian'   => 'nullable|numeric|min:0|max:100',
            'uts'              => 'nullable|numeric|min:0|max:100',
            'uas'              => 'nullable|numeric|min:0|max:100',
        ]);

        $nilai = Nilai::findOrFail($id);

        $nilaiArray = array_filter([
            $request->tugas,
            $request->ulangan_harian,
            $request->uts,
            $request->uas
        ], fn($v) => $v !== null);

        $rataRata = count($nilaiArray) > 0 ? array_sum($nilaiArray) / count($nilaiArray) : null;

        $nilai->update([
            'mata_pelajaran'   => $request->mata_pelajaran,
            'tugas'            => $request->tugas,
            'ulangan_harian'   => $request->ulangan_harian,
            'uts'              => $request->uts,
            'uas'              => $request->uas,
            'rata_rata'        => $rataRata,
        ]);

        return redirect()->route('guru.nilai.detail', $nilai->murid_id)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * 8️⃣ Hapus nilai
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return back()->with('success', 'Nilai berhasil dihapus.');
    }
}
