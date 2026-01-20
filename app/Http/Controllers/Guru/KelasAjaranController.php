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
    /* ============================================================
       HELPER: AMBIL NAMA GURU (AMAN)
    ============================================================ */
    private function namaGuru()
    {
        return trim(strtolower(Auth::user()->guru->nama_lengkap ?? ''));
    }

    /* ============================================================
       HELPER: CEK GURU MENGAJAR DI KELAS
    ============================================================ */
    private function guruMengajar($kelas_id)
    {
        $namaGuru = $this->namaGuru();

        if ($namaGuru === '') {
            return false;
        }

        return Jadwal::where('kelas_id', $kelas_id)
            ->whereRaw('LOWER(TRIM(guru)) = ?', [$namaGuru])
            ->exists();
    }

    /* ============================================================
       INDEX KELAS AJARAN
    ============================================================ */
    public function index()
    {
        $guru = Auth::user()->guru;
        if (!$guru) abort(403);

        $namaGuru = $this->namaGuru();

        $kelasAjaran = Jadwal::with('kelas.guru')
            ->whereRaw('LOWER(TRIM(guru)) = ?', [$namaGuru])
            ->get()
            ->unique('kelas_id')
            ->map(function ($jadwal) {
                preg_match('/\d+/', $jadwal->kelas->nama_kelas, $match);

                return [
                    'id'        => $jadwal->kelas->id,
                    'kelas'     => $jadwal->kelas->nama_kelas,
                    'guru'      => $jadwal->kelas->guru->nama_lengkap ?? '-',
                    'deskripsi' => $jadwal->kelas->deskripsi ?? '-',
                    'level'     => $match[0] ?? null,
                ];
            })
            ->values();

        return view('guru.manajemen-kelas.kelas-ajaran.index', compact('kelasAjaran'));
    }

    /* ============================================================
       DETAIL KELAS AJARAN
    ============================================================ */
    public function detail($id)
    {
        if (!$this->guruMengajar($id)) abort(403);

        $namaGuru = $this->namaGuru();

        $kelas = Kelas::with(['guru.user', 'murids'])->findOrFail($id);

        $jadwal = Jadwal::where('kelas_id', $id)
            ->whereRaw('LOWER(TRIM(guru)) = ?', [$namaGuru])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->first();

        $sesi = Sesi::where('kelas_id', $id)
            ->whereHas('jadwal', function ($q) use ($namaGuru) {
                $q->whereRaw('LOWER(TRIM(guru)) = ?', [$namaGuru]);
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
        ]);
    }

    /* ============================================================
       STORE SESI
    ============================================================ */
    public function storeSesi(Request $request)
    {
        if (!$this->guruMengajar($request->kelas_id)) abort(403);

        $request->validate([
            'kelas_id'    => 'required|exists:kelas,id',
            'judul_sesi'  => 'required|string|max:255',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        $namaGuru = $this->namaGuru();

        $jadwal = Jadwal::where('kelas_id', $request->kelas_id)
            ->whereRaw('LOWER(TRIM(guru)) = ?', [$namaGuru])
            ->where('tanggal', $request->tanggal)
            ->where('jam_mulai', $request->jam_mulai)
            ->first();

        if (!$jadwal) {
            return back()->with('error', 'Jadwal tidak ditemukan.');
        }

        Sesi::create([
            'kelas_id'    => $request->kelas_id,
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
       EDIT AKTIVITAS PEMBELAJARAN (EDIT SESI)
    ============================================================ */
    public function editSesi($id)
    {
        $sesi = Sesi::findOrFail($id);

        if (!$this->guruMengajar($sesi->kelas_id)) abort(403);

        $kelas = Kelas::findOrFail($sesi->kelas_id);

        return view(
            'guru.manajemen-kelas.kelas-ajaran.detail-kelas.edit-aktivitas-pembelajaran',
            [
                'sesi'     => $sesi,
                'kelas_id' => $kelas->id
            ]
        );
    }

    /* ============================================================
       UPDATE SESI
    ============================================================ */
    public function updateSesi(Request $request, $id)
    {
        $sesi = Sesi::findOrFail($id);
        if (!$this->guruMengajar($sesi->kelas_id)) abort(403);

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
            ->route('guru.kelas.ajaran.detail', $sesi->kelas_id)
            ->with('success', 'Sesi berhasil diperbarui!');
    }

    /* ============================================================
       UPLOAD MODUL (MULTI MODUL PER SESI)
    ============================================================ */
    public function uploadModul(Request $request)
    {
        if (!$this->guruMengajar($request->kelas_id)) abort(403);

        $request->validate([
            'kelas_id'     => 'required|exists:kelas,id',
            'sesi_id'      => 'required|exists:sesi,id',
            'judul_materi' => 'required|string|max:255',
            'file_materi'  => 'required|mimes:pdf|max:102400',
        ]);

        $sesi = Sesi::findOrFail($request->sesi_id);

        $file = $request->file('file_materi');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('modul', $fileName, 'public');

        Modul::create([
            'kelas_id'  => $request->kelas_id,
            'sesi_id'   => $sesi->id,
            'jadwal_id' => $sesi->jadwal_id,
            'judul'     => $request->judul_materi,
            'file'      => $path,
        ]);

        return back()->with('success', 'Modul berhasil diunggah!');
    }

    /* ============================================================
       EDIT / UPDATE / DELETE / PREVIEW MODUL
    ============================================================ */
    public function editModul($id)
    {
        $modul = Modul::findOrFail($id);
        if (!$this->guruMengajar($modul->kelas_id)) abort(403);

        $kelas = Kelas::findOrFail($modul->kelas_id);

        return view(
            'guru.manajemen-kelas.kelas-ajaran.detail-kelas.edit-modul',
            compact('modul', 'kelas')
        );
    }

    public function updateModul(Request $request, $id)
    {
        $modul = Modul::findOrFail($id);
        if (!$this->guruMengajar($modul->kelas_id)) abort(403);

        $request->validate([
            'judul_materi' => 'required|string|max:255',
            'file_materi'  => 'nullable|mimes:pdf|max:102400',
        ]);

        $modul->judul   = $request->judul_materi;
        $modul->topik   = $request->topik;
        $modul->catatan = $request->catatan;

        if ($request->hasFile('file_materi')) {
            if ($modul->file && Storage::disk('public')->exists($modul->file)) {
                Storage::disk('public')->delete($modul->file);
            }

            $file = $request->file('file_materi');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $modul->file = $file->storeAs('modul', $fileName, 'public');
        }

        $modul->save();

        return redirect()
            ->route('guru.kelas.ajaran.detail', $modul->kelas_id)
            ->with('success', 'Modul berhasil diperbarui!');
    }

    public function deleteModul($id)
    {
        $modul = Modul::findOrFail($id);
        if (!$this->guruMengajar($modul->kelas_id)) abort(403);

        if ($modul->file && Storage::disk('public')->exists($modul->file)) {
            Storage::disk('public')->delete($modul->file);
        }

        $modul->delete();

        return back()->with('success', 'Modul berhasil dihapus!');
    }

    public function previewModul($id)
    {
        $modul = Modul::findOrFail($id);
        if (!$this->guruMengajar($modul->kelas_id)) abort(403);

        return response()->file(
            storage_path('app/public/' . $modul->file),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline'
            ]
        );
    }
}
