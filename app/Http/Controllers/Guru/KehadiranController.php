<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Sesi;
use App\Models\Murid;
use App\Models\Kelas;
use App\Models\Kehadiran;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    public function index($id)
    {
        $sesi = Sesi::findOrFail($id);

        // ambil kelas & siswa
        $kelas  = Kelas::with('murids')->findOrFail($sesi->kelas_id);
        $murids = $kelas->murids;

        // ambil presensi lama
        $kehadiran = Kehadiran::where('sesi_id', $id)
            ->get()
            ->keyBy('murid_id');

        // nomor sesi otomatis
        $semuaSesi = Sesi::where('kelas_id', $sesi->kelas_id)
            ->orderBy('id')
            ->get();

        $nomorSesi = $semuaSesi->search(fn($ss) => $ss->id == $sesi->id) + 1;

        // ambil jadwal berdasarkan kelas
        $jadwal = Jadwal::where('kelas_id', $sesi->kelas_id)->first();

        return view(
            'guru.manajemen-kelas.kelas-ajaran.detail-kelas.kehadiran.presensi',
            compact('sesi', 'kelas', 'murids', 'kehadiran', 'nomorSesi', 'jadwal')
        );
    }

    public function store(Request $request, $id)
    {
        $statuses = $request->input('status', []);

        foreach ($statuses as $muridId => $stat) {
            Kehadiran::updateOrCreate(
                [
                    'sesi_id'  => $id,
                    'murid_id' => $muridId
                ],
                [
                    'status' => $stat
                ]
            );
        }

        // setelah simpan kembali ke halaman Diskusi
        $sesi = Sesi::findOrFail($id);

        return redirect()
            ->route('guru.kelas.ajaran.detail', $sesi->kelas_id)
            ->with('success', 'Presensi berhasil disimpan.');
    }
}
