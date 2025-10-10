<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    // Menampilkan kalender jadwal
    public function index()
    {
        return view('admin.jadwal.index');
    }

    // Mengambil data jadwal dalam format JSON untuk FullCalendar
    public function getJadwal()
    {
        $jadwal = Jadwal::with('kelas')->get();

        $events = $jadwal->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->kelas->nama_kelas . ' - ' . $item->nama_mapel,
                'start' => $item->tanggal,
            ];
        });

        return response()->json($events);
    }

    // Menampilkan form tambah jadwal
    public function create(Request $request)
    {
        $kelas = Kelas::all();
        $tanggal = $request->query('tanggal');
        return view('admin.jadwal.create', compact('kelas', 'tanggal'));
    }

    // Menyimpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'nama_mapel' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // Menampilkan form edit jadwal
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.jadwal.edit', compact('jadwal', 'kelas'));
    }

    // Mengupdate jadwal
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'kelas_id' => 'required',
            'nama_mapel' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil diperbarui!');
    }

    // Menghapus jadwal
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus!');
    }
}
