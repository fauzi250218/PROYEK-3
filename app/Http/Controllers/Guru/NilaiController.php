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
     * 1️⃣ Tampilkan semua kelas
     */
    public function semuaKelas()
    {
        $guru = Auth::user()->guru;
        $mapelGuru = $guru->mata_pelajaran;

        $kelasList = Kelas::with(['guru.user'])
            ->whereHas('jadwals', function ($query) use ($mapelGuru) {
                $query->where('mata_pelajaran', $mapelGuru);
            })
            ->get();

        return view('guru.nilai.semua-kelas', compact('kelasList'));
    }

    /**
     * 2️⃣ Tampilkan daftar mata pelajaran pada kelas tertentu
     */
    public function index($id)
    {
        $kelas = Kelas::with(['guru.user'])->findOrFail($id);

        $mapelList = Jadwal::where('kelas_id', $id)
            ->pluck('mata_pelajaran')
            ->unique();

        return view('guru.nilai.mapel', compact('kelas', 'mapelList'));
    }

    /**
     * 3️⃣ Tampilkan daftar murid dalam mapel
     */
    public function muridPerMapel($kelasId, $mapel)
    {
        $kelas = Kelas::with('murids')->findOrFail($kelasId);
        $murids = $kelas->murids;

        return view('guru.nilai.murid-mapel', [
            'kelas' => $kelas,
            'murids' => $murids,
            'mataPelajaran' => $mapel
        ]);
    }

    /**
     * 4️⃣ Halaman detail nilai murid
     */
    public function detail($id, $mapel)
    {
        $murid = Murid::with(['kelas', 'nilai'])->findOrFail($id);

        $mapelList = Jadwal::where('kelas_id', $murid->kelas_id)
            ->pluck('mata_pelajaran')
            ->unique();

        return view('guru.nilai.detail', [
            'murid' => $murid,
            'mapelList' => $mapelList,
            'selectedMapel' => $mapel
        ]);
    }

    /**
     * 5️⃣ Simpan nilai baru
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
        $guruId = Auth::user()->guru->id;

        // hitung rata-rata
        $nilaiArray = array_filter([
            $request->tugas,
            $request->ulangan_harian,
            $request->uts,
            $request->uas
        ], fn($v) => $v !== null);

        $rataRata = count($nilaiArray) ? array_sum($nilaiArray) / count($nilaiArray) : null;

        Nilai::create([
            'murid_id'         => $murid->id,
            'kelas_id'         => $murid->kelas_id,
            'guru_id'          => $guruId,
            'mata_pelajaran'   => $request->mata_pelajaran,
            'tugas'            => $request->tugas,
            'ulangan_harian'   => $request->ulangan_harian,
            'uts'              => $request->uts,
            'uas'              => $request->uas,
            'rata_rata'        => $rataRata,
        ]);

        return redirect()->route('guru.nilai.detail', [
            'id' => $murid->id,
            'mapel' => $request->mata_pelajaran
        ])->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * 6️⃣ Update nilai
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

        $rataRata = count($nilaiArray) ? array_sum($nilaiArray) / count($nilaiArray) : null;

        $nilai->update([
            'mata_pelajaran'   => $request->mata_pelajaran,
            'tugas'            => $request->tugas,
            'ulangan_harian'   => $request->ulangan_harian,
            'uts'              => $request->uts,
            'uas'              => $request->uas,
            'rata_rata'        => $rataRata,
        ]);

        return redirect()->route('guru.nilai.detail', [
            'id' => $nilai->murid_id,
            'mapel' => $nilai->mata_pelajaran
        ])->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * 7️⃣ Hapus nilai
     */
    public function destroy($id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->delete();

        return back()->with('success', 'Nilai berhasil dihapus.');
    }
}
