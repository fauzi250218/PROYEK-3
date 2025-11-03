<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\Guru;
use App\Models\Jadwal;

class KelasController extends Controller
{
    /**
     * 🔹 Tampilkan daftar kelas (group per jenjang)
     */
    public function index()
    {
        $kelas = Kelas::with(['guru.user', 'murids'])
            ->get()
            ->groupBy(function ($item) {
                return substr($item->nama_kelas, 0, 1);
            });

        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * 🔹 Form Tambah Kelas
     */
    public function create()
    {
        // Guru yang belum punya kelas binaan
        $guru = Guru::whereDoesntHave('kelas')->with('user')->get();
        return view('admin.kelas.create', compact('guru'));
    }

    /**
     * 🔹 Simpan Data Kelas Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'guru_id'    => 'nullable|exists:guru,id',
            'deskripsi'  => 'nullable|string',
        ]);

        // Buat kelas baru
        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'guru_id'    => $request->guru_id,
            'deskripsi'  => $request->deskripsi,
        ]);

        // 🔹 Sinkronkan dengan guru jika dipilih
        if ($request->guru_id) {
            $guru = Guru::find($request->guru_id);
            if ($guru) {
                $guru->kelas()->save($kelas);
            }
        }

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * 🔹 Form Edit Kelas
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $guru = Guru::whereDoesntHave('kelas')
            ->orWhere('id', $kelas->guru_id)
            ->with('user')
            ->get();

        return view('admin.kelas.edit', compact('kelas', 'guru'));
    }

    /**
     * 🔹 Update Data Kelas
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'guru_id'    => 'nullable|exists:guru,id',
            'deskripsi'  => 'nullable|string',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'guru_id'    => $request->guru_id,
            'deskripsi'  => $request->deskripsi,
        ]);

        // 🔹 Perbarui wali kelas jika diubah
        if ($request->guru_id) {
            $guru = Guru::find($request->guru_id);
            if ($guru) {
                $guru->kelas()->save($kelas);
            }
        }

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * 🔹 Hapus Data Kelas
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * 🔹 Halaman Kelola Siswa & Jadwal Kelas
     */
    public function kelolaMurid($id)
    {
        // Ambil kelas + relasi guru, murid, dan jadwal
        $kelas = Kelas::with(['murids', 'guru.user', 'jadwals'])->findOrFail($id);

        // Murid yang belum punya kelas
        $muridBelumMasukKelas = Murid::whereNull('kelas_id')
            ->orderBy('nama')
            ->get()
            ->groupBy(function ($murid) {
                return substr($murid->nis, 0, 1);
            });

        // Murid dalam kelas saat ini
        $muridDalamKelas = $kelas->murids;

        // 🔹 Ambil jadwal khusus kelas ini (bukan jadwal guru)
        $jadwalKelas = $kelas->jadwals
            ->sortBy(['hari', 'jam_mulai'])
            ->values();

        return view('admin.kelas.kelola-murid', compact(
            'kelas',
            'muridBelumMasukKelas',
            'muridDalamKelas',
            'jadwalKelas'
        ));
    }

    /**
     * 🔹 Tambahkan Murid ke Kelas
     */
    public function tambahMurid(Request $request, $id)
    {
        $request->validate([
            'murid_ids' => 'required|array',
        ]);

        Murid::whereIn('id', $request->murid_ids)->update(['kelas_id' => $id]);

        return redirect()->back()->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    /**
     * 🔹 Hapus Murid dari Kelas
     */
    public function hapusMurid($kelas_id, $murid_id)
    {
        $murid = Murid::findOrFail($murid_id);
        $murid->update(['kelas_id' => null]);

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
}
