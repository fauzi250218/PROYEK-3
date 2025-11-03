<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Throwable;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /** 🗓️ Halaman utama kalender jadwal */
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $guru = Guru::with('user')->get();
        return view('admin.jadwal.index', compact('kelas', 'guru'));
    }

    /** 📅 Mengambil semua jadwal (untuk kalender) */
    public function getJadwal()
    {
        $jadwal = Jadwal::with('kelas')->get();

        $events = $jadwal->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => "{$item->kelas->nama_kelas} - {$item->mata_pelajaran} ({$item->guru})",
                'start' => "{$item->tanggal}T{$item->jam_mulai}",
                'end' => "{$item->tanggal}T{$item->jam_selesai}",
                'backgroundColor' => '#' . substr(md5($item->mata_pelajaran), 0, 6),
            ];
        });

        return response()->json($events);
    }

    /** 📆 Mengambil jadwal berdasarkan tanggal */
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
                    'tanggal' => $item->tanggal,
                ];
            });

        return response()->json($jadwal);
    }

    /** 💾 Simpan jadwal baru — bisa berulang 1 semester */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mata_pelajaran' => 'required|string|max:100',
                'guru' => 'required|string|max:100',
                'tanggal' => 'required|date',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required|after:jam_mulai',
                'ulang' => 'nullable|string|in:sekali,semester', // tambahan
            ]);

            $ulang = $request->ulang ?? 'semester'; // default semester
            $tanggalMulai = Carbon::parse($request->tanggal);

            // kalau "sekali", cukup buat 1 jadwal saja
            if ($ulang === 'sekali') {
                return $this->buatJadwal($request, $tanggalMulai);
            }

            // kalau "semester" → ulang tiap minggu selama 6 bulan
            $tanggalAkhir = $tanggalMulai->copy()->addMonths(6);
            $createdCount = 0;

            while ($tanggalMulai <= $tanggalAkhir) {
                $buat = $this->buatJadwal($request, $tanggalMulai, false);
                if ($buat) $createdCount++;
                $tanggalMulai->addWeek(); // tambah 7 hari
            }

            return response()->json([
                'success' => true,
                'message' => "Jadwal berhasil disimpan dan diulang setiap minggu selama 1 semester ($createdCount kali)."
            ]);
        } 
        catch (ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } 
        catch (Throwable $e) {
            Log::error('💥 Gagal menyimpan jadwal: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    /** 🔧 Fungsi bantu untuk menyimpan jadwal dan cek bentrok */
    private function buatJadwal(Request $request, $tanggal, $returnResponse = true)
    {
        $tanggalStr = $tanggal->format('Y-m-d');

        // cek bentrok
        $bentrok = Jadwal::where('kelas_id', $request->kelas_id)
            ->whereDate('tanggal', $tanggalStr)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();

        if ($bentrok) {
            if ($returnResponse) {
                return response()->json([
                    'success' => false,
                    'message' => "Jadwal bentrok pada tanggal $tanggalStr."
                ], 422);
            }
            return false;
        }

        Jadwal::create([
            'kelas_id' => $request->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'guru' => $request->guru,
            'tanggal' => $tanggalStr,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        if ($returnResponse) {
            return response()->json(['success' => true, 'message' => 'Jadwal berhasil disimpan!']);
        }

        return true;
    }

    /** ✏️ Mengambil detail satu jadwal */
    public function show($id)
    {
        $jadwal = Jadwal::with('kelas')->findOrFail($id);
        return response()->json($jadwal);
    }

    /** 🔄 Update jadwal (via modal edit) */
    public function update(Request $request, $id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);

            $request->validate([
                'mata_pelajaran' => 'required|string|max:100',
                'guru' => 'required|string|max:100',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required|after:jam_mulai',
            ]);

            $jadwal->update([
                'mata_pelajaran' => $request->mata_pelajaran,
                'guru' => $request->guru,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
            ]);

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui!']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /** 🗑️ Hapus 1 jadwal (per hari) */
    public function destroy($id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus!']);
        } catch (Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus jadwal.'], 500);
        }
    }

    /** 🗓️ Hapus semua jadwal satu mata pelajaran di semester */
    public function deleteSemester($mataPelajaran)
    {
        try {
            Jadwal::where('mata_pelajaran', $mataPelajaran)->delete();
            return response()->json([
                'success' => true,
                'message' => "Semua jadwal '$mataPelajaran' di semester ini telah dihapus."
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jadwal semester: ' . $e->getMessage()
            ], 500);
        }
    }
}
