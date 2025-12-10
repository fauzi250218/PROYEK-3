<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Sesi;
use App\Models\Modul;

class KelasAjaranController extends Controller
{
    // ============================================
    // INDEX
    // ============================================
    public function index()
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            abort(403, 'Akun ini bukan guru.');
        }

        $kelasAjaran = Kelas::where('guru_id', $guru->id)->get()
            ->map(function ($k) use ($guru) {

                preg_match('/\d+/', $k->nama_kelas, $match);
                $level = $match[0] ?? null;

                return [
                    'id'        => $k->id,
                    'kelas'     => $k->nama_kelas,
                    'guru'      => $guru->nama_lengkap,
                    'deskripsi' => $k->deskripsi,
                    'level'     => $level,
                ];
            });

        return view('guru.manajemen-kelas.kelas-ajaran.index', compact('kelasAjaran'));
    }



    // ============================================
    // DETAIL KELAS AJARAN
    // ============================================
    public function detail($id)
    {
        $kelas = Kelas::with(['guru.user', 'murids'])->findOrFail($id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki akses ke kelas ini.");
        }

        $jadwal = Jadwal::where('kelas_id', $id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->first();

        $sesi = Sesi::where('kelas_id', $id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $modul = Modul::where('kelas_id', $id)->get();

        $detail = [
            'nama_kelas'   => $kelas->nama_kelas,
            'mapel'        => $kelas->guru->mata_pelajaran ?? '-',
            'wali_kelas'   => $kelas->guru->nama_lengkap ?? '-',
            'jumlah_siswa' => $kelas->murids->count(),
        ];

        return view('guru.manajemen-kelas.kelas-ajaran.detail-kelas.detail', [
            'detail'    => $detail,
            'jadwal'    => $jadwal,
            'sesi'      => $sesi,
            'modul'     => $modul,
            'kelas_id'  => $id,
        ]);
    }



    // ============================================
    // EDIT SESI
    // ============================================
    public function editSesi($id)
    {
        $sesi  = Sesi::findOrFail($id);
        $kelas = Kelas::findOrFail($sesi->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki izin mengedit sesi ini.");
        }

        $detail = [
            'nama_kelas' => $kelas->nama_kelas,
            'mapel'      => $kelas->guru->mata_pelajaran ?? '-',
            'wali_kelas' => $kelas->guru->nama_lengkap ?? '-',
        ];

        return view('guru.manajemen-kelas.kelas-ajaran.detail-kelas.edit-aktivitas-pembelajaran', [
            'sesi'      => $sesi,
            'kelas'     => $kelas,
            'detail'    => $detail,
            'kelas_id'  => $kelas->id,
        ]);
    }



    // ============================================
    // UPDATE SESI
    // ============================================
    public function updateSesi(Request $request, $id)
    {
        $sesi  = Sesi::findOrFail($id);
        $kelas = Kelas::findOrFail($sesi->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, 'Anda tidak memiliki izin mengubah sesi ini.');
        }

        $request->validate([
            'judul_sesi'  => 'required|string|max:255',
            'topik'       => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $sesi->update([
            'judul_sesi'  => $request->judul_sesi,
            'topik'       => $request->topik,
            'deskripsi'   => $request->deskripsi,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()
            ->route('guru.kelas.ajaran.detail', $kelas->id)
            ->with('success', 'Sesi pembelajaran berhasil diperbarui!');
    }



    // ============================================
    // HAPUS SESI
    // ============================================
    public function deleteSesi($id)
    {
        $sesi  = Sesi::findOrFail($id);
        $kelas = Kelas::findOrFail($sesi->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki izin menghapus sesi ini.");
        }

        $sesi->delete();

        return redirect()
            ->route('guru.kelas.ajaran.detail', $kelas->id)
            ->with('success', 'Sesi pembelajaran berhasil dihapus!');
    }



    // ============================================
    // STORE SESI BARU
    // ============================================
    public function storeSesi(Request $request)
    {
        $request->validate([
            'kelas_id'   => 'required|exists:kelas,id',
            'judul_sesi' => 'required|string|max:255',
            'tanggal'    => 'required|date',
            'jam_mulai'  => 'required',
            'jam_selesai'=> 'required|after:jam_mulai',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki izin menambah sesi untuk kelas ini.");
        }

        Sesi::create([
            'kelas_id'    => $kelas->id,
            'judul_sesi'  => $request->judul_sesi,
            'topik'       => $request->input('topik'),
            'deskripsi'   => $request->input('deskripsi'),
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->back()->with('success', 'Sesi pembelajaran berhasil ditambahkan!');
    }



    // ============================================
    // SYNC SESI DARI JADWAL
    // ============================================
    public function syncSesi($kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki akses.");
        }

        $jadwalList = Jadwal::where('kelas_id', $kelas_id)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        if ($jadwalList->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada jadwal yang bisa disinkronkan.'
            ], 400);
        }

        $count = 0;

        foreach ($jadwalList as $index => $jadwal) {

            $exists = Sesi::where('kelas_id', $kelas_id)
                ->where('tanggal', $jadwal->tanggal)
                ->where('jam_mulai', $jadwal->jam_mulai)
                ->first();

            if ($exists) continue;

            Sesi::create([
                'kelas_id'    => $kelas_id,
                'judul_sesi'  => 'Sesi ' . ($index + 1),
                'topik'       => null,
                'deskripsi'   => null,
                'tanggal'     => $jadwal->tanggal,
                'jam_mulai'   => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai,
            ]);

            $count++;
        }

        return response()->json([
            'message' => "$count sesi berhasil disinkronkan."
        ]);
    }



    // ============================================
    // UPLOAD MODUL
    // ============================================
    public function uploadModul(Request $request)
    {
        $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'sesi_id'        => 'required|exists:sesi,id',
            'judul_materi'   => 'required|string|max:255',
            'file_materi'    => 'required|mimes:pdf|max:20480',
            'topik_sesi'     => 'nullable|string|max:255',
            'catatan_materi' => 'nullable|string',
        ]);

        $file = $request->file('file_materi');
        $fileName = time().'_'.$file->getClientOriginalName();
        $path = $file->storeAs('modul', $fileName, 'public');

        Modul::create([
            'kelas_id'   => $request->kelas_id,
            'sesi_id'    => $request->sesi_id,
            'judul'      => $request->judul_materi,
            'file'       => $path,
            'topik'      => $request->topik_sesi,
            'catatan'    => $request->catatan_materi,
        ]);

        return redirect()->back()->with('success', 'Modul berhasil diupload!');
    }



    // ============================================
    // EDIT MODUL
    // ============================================
    public function editModul($id)
    {
        $modul = Modul::findOrFail($id);
        $kelas = Kelas::findOrFail($modul->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki akses untuk mengedit modul ini.");
        }

        return view('guru.manajemen-kelas.kelas-ajaran.detail-kelas.edit-modul', [
            'modul' => $modul,
            'kelas' => $kelas
        ]);
    }



    // ============================================
    // UPDATE MODUL
    // ============================================
    public function updateModul(Request $request, $id)
    {
        $modul = Modul::findOrFail($id);
        $kelas = Kelas::findOrFail($modul->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki akses memperbarui modul ini.");
        }

        $request->validate([
            'judul'   => 'required|string|max:255',
            'topik'   => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'file'    => 'nullable|mimes:pdf|max:20480',
        ]);

        $modul->judul   = $request->judul;
        $modul->topik   = $request->topik;
        $modul->catatan = $request->catatan;

        if ($request->hasFile('file')) {

            // Hapus file lama
            if ($modul->file && Storage::disk('public')->exists($modul->file)) {
                Storage::disk('public')->delete($modul->file);
            }

            $file = $request->file('file');
            $fileName = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('modul', $fileName, 'public');

            $modul->file = $path;
        }

        $modul->save();

        return redirect()
            ->route('guru.kelas.ajaran.detail', $kelas->id)
            ->with('success', 'Modul berhasil diperbarui!');
    }



    // ============================================
    // DELETE MODUL
    // ============================================
    public function deleteModul($id)
    {
        $modul = Modul::findOrFail($id);
        $kelas = Kelas::findOrFail($modul->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) {
            abort(403, "Anda tidak memiliki izin menghapus modul ini.");
        }

        if ($modul->file && Storage::disk('public')->exists($modul->file)) {
            Storage::disk('public')->delete($modul->file);
        }

        $modul->delete();

        return redirect()
            ->route('guru.kelas.ajaran.detail', $kelas->id)
            ->with('success', 'Modul berhasil dihapus!');
    }



    // ==========================================================
    // VIEW PDF INLINE (ANTI DOWNLOAD, ANTI BLANK PREVIEW)
    // ==========================================================
    public function viewPdf($path)
    {
        $full = storage_path('app/public/' . $path);

        if (!file_exists($full)) {
            abort(404);
        }

        return response()->file($full, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($full).'"'
        ]);
    }
}

