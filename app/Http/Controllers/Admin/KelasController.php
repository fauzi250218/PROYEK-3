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
     * 🔹 Tampilkan daftar kelas (group per jenjang) + JADWAL
     */
    public function index()
    {
        // Ambil guru + murid + JADWAL kelas
        $kelas = Kelas::with([
                'guru.user',
                'murids',
                'jadwals' => function ($q) {
                    $q->orderBy('tanggal')->orderBy('jam_mulai');
                }
            ])
            ->get()
            ->groupBy(function ($item) {
                return substr($item->nama_kelas, 0, 1); // group berdasarkan angka awal (7,8,9)
            });

        return view('admin.kelas.index', compact('kelas'));
    }

    /**
     * 🔹 Form Tambah Kelas
     */
    public function create()
    {
        // Guru yang belum punya kelas
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

        // Simpan kelas
        $kelas = Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'guru_id'    => $request->guru_id,
            'deskripsi'  => $request->deskripsi,
        ]);

        // Jika guru dipilih → jadikan wali kelas
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

        // Guru yg belum punya kelas + wali kelas sekarang
        $guru = Guru::whereDoesntHave('kelas')
            ->orWhere('id', $kelas->guru_id)
            ->with('user')
            ->get();

        return view('admin.kelas.edit', compact('kelas', 'guru'));
    }

    /**
     * 🔹 Update Kelas
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

        // Update wali kelas bila berubah
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
     * 🔹 Hapus Kelas
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Hapus kelas (murid tetap aman)
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * 🔹 Halaman Kelola Siswa & Jadwal pada kelas
     */
    public function kelolaMurid($id)
    {
        // Ambil kelas + guru + murid + jadwal
        $kelas = Kelas::with(['murids', 'guru.user', 'jadwals'])->findOrFail($id);

        // Murid yang belum masuk kelas manapun
        $muridBelumMasukKelas = Murid::whereNull('kelas_id')
            ->orderBy('nama')
            ->get()
            ->groupBy(function ($murid) {
                return substr($murid->nis, 0, 1);
            });

        // Murid dalam kelas
        $muridDalamKelas = $kelas->murids;

        // Sort jadwal berdasarkan hari & jam
        $jadwalKelas = $kelas->jadwals
            ->sortBy(['tanggal', 'jam_mulai'])
            ->values();

        return view('admin.kelas.kelola-murid', compact(
            'kelas',
            'muridBelumMasukKelas',
            'muridDalamKelas',
            'jadwalKelas'
        ));
    }

    /**
     * 🔹 Tambahkan murid ke kelas
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
     * 🔹 Hapus murid dari kelas
     */
    public function hapusMurid($kelas_id, $murid_id)
    {
        $murid = Murid::findOrFail($murid_id);

        $murid->update(['kelas_id' => null]);

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari kelas.');
    }
}
