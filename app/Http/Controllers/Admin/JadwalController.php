<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Menampilkan halaman kalender jadwal.
     */
    public function index()
    {
        return view('admin.jadwal.index');
    }

    /**
     * Mengambil data jadwal dalam format JSON untuk FullCalendar.
     */
    public function getJadwal()
    {
        $jadwal = Jadwal::with('kelas')->get();

        $events = $jadwal->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->kelas->nama_kelas . ' - ' . $item->mata_pelajaran,
                'start' => $item->tanggal,
                'backgroundColor' => '#' . substr(md5($item->mata_pelajaran), 0, 6),
            ];
        });

        return response()->json($events);
    }

    /**
     * Mengambil semua jadwal berdasarkan tanggal (untuk modal lihat jadwal hari itu).
     */
    public function getByTanggal($tanggal)
    {
        $jadwal = Jadwal::with('kelas')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'mata_pelajaran' => $item->mata_pelajaran,
                    'guru' => $item->guru,
                    'jam_mulai' => $item->jam_mulai,
                    'jam_selesai' => $item->jam_selesai,
                    'kelas_nama' => $item->kelas->nama_kelas ?? '-',
                ];
            });

        return response()->json($jadwal);
    }

    /**
     * Menampilkan form tambah jadwal.
     */
    public function create(Request $request)
    {
        $kelas = Kelas::all();
        $tanggal = $request->query('tanggal');
        return view('admin.jadwal.create', compact('kelas', 'tanggal'));
    }

    /**
     * Menyimpan jadwal baru dan otomatis mengulang selama 1 semester.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'mata_pelajaran' => 'required|string|max:100',
            'guru' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        // grup unik agar bisa dihapus massal nanti
        $groupId = uniqid('jadwal_');

        // buat jadwal berulang selama 16 minggu (1 semester)
        for ($i = 0; $i < 16; $i++) {
            Jadwal::create([
                'group_id' => $groupId,
                'kelas_id' => $request->kelas_id,
                'mata_pelajaran' => $request->mata_pelajaran,
                'guru' => $request->guru,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'tanggal' => date('Y-m-d', strtotime("+$i week", strtotime($request->tanggal))),
                'keterangan' => $request->keterangan,
            ]);
        }

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan dan otomatis diulang selama 1 semester!');
    }

    /**
     * Menampilkan form edit jadwal.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.jadwal.edit', compact('jadwal', 'kelas'));
    }

    /**
     * Memperbarui data jadwal.
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'kelas_id' => 'required',
            'mata_pelajaran' => 'required|string|max:100',
            'guru' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui!');
    }

    /**
     * Menghapus jadwal (satu atau semua jadwal berulang berdasarkan group_id).
     */
    public function destroy($id, Request $request)
    {
        $jadwal = Jadwal::findOrFail($id);

        if ($request->has('hapus_semua') && $request->hapus_semua == 1 && $jadwal->group_id) {
            Jadwal::where('group_id', $jadwal->group_id)->delete();
            return redirect()->route('admin.jadwal.index')
                ->with('success', 'Semua jadwal berulang dalam 1 semester berhasil dihapus!');
        }

        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus!');
    }
}
