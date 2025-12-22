<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Sesi;
use App\Models\Modul;

class KelasAjaranController extends Controller
{
    /* ============================================================
       INDEX KELAS AJARAN
    ============================================================ */
    public function index()
    {
        $guru = Auth::user()->guru;
        if (!$guru) abort(403, 'Akun ini bukan guru.');

        $kelasAjaran = Kelas::where('guru_id', $guru->id)
            ->orderBy('nama_kelas')
            ->get()
            ->map(function ($k) use ($guru) {
                preg_match('/\d+/', $k->nama_kelas, $match);

                return [
                    'id'        => $k->id,
                    'kelas'     => $k->nama_kelas,
                    'guru'      => $guru->nama_lengkap,
                    'deskripsi' => $k->deskripsi,
                    'level'     => $match[0] ?? null,
                ];
            });

        return view('guru.manajemen-kelas.kelas-ajaran.index', compact('kelasAjaran'));
    }

    /* ============================================================
       DETAIL KELAS AJARAN
    ============================================================ */
    public function detail($id)
    {
        $guru = Auth::user()->guru;
        if (!$guru) abort(403);

        $namaGuru = $guru->nama_lengkap;

        $kelas = Kelas::with(['guru.user', 'murids'])->findOrFail($id);
        if ($kelas->guru_id !== $guru->id) abort(403);

        // 🔴 PENTING: filter jadwal pakai NAMA GURU
        $jadwal = Jadwal::where('kelas_id', $id)
            ->where('guru', $namaGuru)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->first();

        // 🔴 PENTING: sesi hanya dari jadwal guru login
        $sesi = Sesi::where('kelas_id', $id)
            ->whereHas('jadwal', function ($q) use ($namaGuru) {
                $q->where('guru', $namaGuru);
            })
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $modul = Modul::where('kelas_id', $id)
            ->orderBy('created_at')
            ->get();

        return view('guru.manajemen-kelas.kelas-ajaran.detail-kelas.detail', [
            'detail' => [
                'nama_kelas'   => $kelas->nama_kelas,
                'mapel'        => $kelas->guru->mata_pelajaran ?? '-',
                'wali_kelas'   => $kelas->guru->nama_lengkap ?? '-',
                'jumlah_siswa' => $kelas->murids->count(),
            ],
            'jadwal'   => $jadwal,
            'sesi'     => $sesi,
            'modul'    => $modul,
            'kelas_id' => $id,
            'guru'     => $guru,
        ]);
    }

    /* ============================================================
       STORE SESI
    ============================================================ */
    public function storeSesi(Request $request)
    {
        $guru = Auth::user()->guru;
        if (!$guru) abort(403);

        $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'judul_sesi'  => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        if ($kelas->guru_id !== $guru->id) abort(403);

        $jadwal = Jadwal::where('kelas_id', $kelas->id)
            ->where('guru', $guru->nama_lengkap)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan.');
        }

        Sesi::create([
            'kelas_id'    => $kelas->id,
            'jadwal_id'   => $jadwal->id,
            'judul_sesi'  => $request->judul_sesi,
            'topik'       => $request->topik,
            'deskripsi'   => $request->deskripsi,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return back()->with('success', 'Sesi berhasil ditambahkan!');
    }

    /* ============================================================
       EDIT SESI
    ============================================================ */
    public function editSesi($id)
    {
        $guru = Auth::user()->guru;

        $sesi  = Sesi::findOrFail($id);
        $kelas = Kelas::findOrFail($sesi->kelas_id);

        if ($kelas->guru_id !== $guru->id) abort(403);

        $jadwal = Jadwal::find($sesi->jadwal_id);

        return view('guru.manajemen-kelas.kelas-ajaran.detail-kelas.edit-aktivitas-pembelajaran', [
            'sesi'     => $sesi,
            'kelas'    => $kelas,
            'jadwal'   => $jadwal,
            'detail'   => [
                'nama_kelas' => $kelas->nama_kelas,
                'mapel'      => $kelas->guru->mata_pelajaran ?? '-',
                'wali_kelas' => $kelas->guru->nama_lengkap ?? '-',
            ],
            'kelas_id' => $kelas->id,
        ]);
    }

    /* ============================================================
       UPDATE SESI
    ============================================================ */
    public function updateSesi(Request $request, $id)
    {
        $guru = Auth::user()->guru;

        $sesi  = Sesi::findOrFail($id);
        $kelas = Kelas::findOrFail($sesi->kelas_id);

        if ($kelas->guru_id !== $guru->id) abort(403);

        $request->validate([
            'judul_sesi'  => 'required|string|max:255',
            'topik'       => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $jadwal = Jadwal::where('kelas_id', $kelas->id)
            ->where('guru', $guru->nama_lengkap)
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $sesi->update([
            'judul_sesi'  => $request->judul_sesi,
            'topik'       => $request->topik,
            'deskripsi'   => $request->deskripsi,
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'jadwal_id'   => $jadwal->id,
        ]);

        return redirect()->route('guru.kelas.ajaran.detail', $kelas->id)
            ->with('success', 'Sesi berhasil diperbarui!');
    }

    /* ============================================================
       DELETE SESI
    ============================================================ */
    public function deleteSesi($id)
    {
        $guru = Auth::user()->guru;

        $sesi  = Sesi::findOrFail($id);
        $kelas = Kelas::findOrFail($sesi->kelas_id);

        if ($kelas->guru_id !== $guru->id) abort(403);

        $sesi->delete();
        return back()->with('success', 'Sesi berhasil dihapus!');
    }

    /* ============================================================
       SYNC SESI OTOMATIS (INI YANG KAMU BUTUHKAN)
    ============================================================ */
    public function syncSesi($kelas_id)
    {
        $guru = Auth::user()->guru;
        if (!$guru) abort(403);

        $kelas = Kelas::findOrFail($kelas_id);
        if ($kelas->guru_id !== $guru->id) abort(403);

        $jadwalList = Jadwal::where('kelas_id', $kelas_id)
            ->where('guru', $guru->nama_lengkap)
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        if ($jadwalList->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada jadwal untuk guru ini.'
            ], 400);
        }

        $count = 0;

        foreach ($jadwalList as $i => $jadwal) {
            if (Sesi::where('jadwal_id', $jadwal->id)->exists()) continue;

            Sesi::create([
                'kelas_id'    => $kelas_id,
                'jadwal_id'   => $jadwal->id,
                'judul_sesi'  => 'Sesi ' . ($i + 1),
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

    /* ============================================================
       UPLOAD MODUL
    ============================================================ */
    public function uploadModul(Request $request)
    {
        $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'sesi_id'        => 'required|exists:sesi,id',
            'judul_materi'   => 'required|string|max:255',
            'file_materi'    => 'required|mimes:pdf|max:102400',
            'topik_sesi'     => 'nullable|string|max:255',
            'catatan_materi' => 'nullable|string',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);
        if ($kelas->guru_id !== Auth::user()->guru->id) abort(403);

        $sesi = Sesi::findOrFail($request->sesi_id);

        $file = $request->file('file_materi');
        $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
        $path = $file->storeAs('modul', $fileName, 'public');

        Modul::create([
            'kelas_id'  => $kelas->id,
            'sesi_id'   => $sesi->id,
            'jadwal_id' => $sesi->jadwal_id,
            'judul'     => $request->judul_materi,
            'file'      => $path,
            'topik'     => $request->topik_sesi,
            'catatan'   => $request->catatan_materi,
        ]);

        return back()->with('success', 'Modul berhasil diunggah!');
    }

    /* ============================================================
       EDIT MODUL
    ============================================================ */
    public function editModul($id)
    {
        $modul = Modul::findOrFail($id);
        $kelas = Kelas::findOrFail($modul->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) abort(403);

        return view('guru.manajemen-kelas.kelas-ajaran.detail-kelas.edit-modul', compact('modul', 'kelas'));
    }

    /* ============================================================
       UPDATE MODUL
    ============================================================ */
    public function updateModul(Request $request, $id)
    {
        $modul = Modul::findOrFail($id);
        $kelas = Kelas::findOrFail($modul->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) abort(403);

        $request->validate([
            'judul'   => 'required|string|max:255',
            'topik'   => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
            'file'    => 'nullable|mimes:pdf|max:102400',
        ]);

        DB::beginTransaction();

        try {
            $oldPath = $modul->file;

            $modul->judul   = $request->judul;
            $modul->topik   = $request->topik;
            $modul->catatan = $request->catatan;

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $modul->file = $file->storeAs('modul', $fileName, 'public');
            }

            $modul->save();
            DB::commit();

            if ($request->hasFile('file') && $oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            return redirect()->route('guru.kelas.ajaran.detail', $kelas->id)
                ->with('success', 'Modul berhasil diperbarui!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui modul.');
        }
    }

    /* ============================================================
       DELETE MODUL
    ============================================================ */
    public function deleteModul($id)
    {
        $modul = Modul::findOrFail($id);
        $kelas = Kelas::findOrFail($modul->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) abort(403);

        if ($modul->file && Storage::disk('public')->exists($modul->file)) {
            Storage::disk('public')->delete($modul->file);
        }

        $modul->delete();

        return redirect()->route('guru.kelas.ajaran.detail', $kelas->id)
            ->with('success', 'Modul berhasil dihapus!');
    }

    /* ============================================================
       PREVIEW MODUL
    ============================================================ */
    public function previewModul($id)
    {
        $modul = Modul::findOrFail($id);
        $kelas = Kelas::findOrFail($modul->kelas_id);

        if ($kelas->guru_id !== Auth::user()->guru->id) abort(403);

        return response()->file(
            storage_path('app/public/' . $modul->file),
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($modul->file) . '"'
            ]
        );
    }
}
