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
    /** ===========================
     * Halaman Kalender
     * =========================== */
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $guru  = Guru::with('user')->get();

        return view('admin.jadwal.index', compact('kelas', 'guru'));
    }

    /** ===========================
     * Ambil semua jadwal (kalender)
     * =========================== */
    public function getJadwal(Request $request)
    {
        $jadwal = Jadwal::with('kelas')
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        $events = $jadwal->map(function ($item) {
            return [
                'id'    => $item->id,
                'title' => "{$item->kelas->nama_kelas} - {$item->mata_pelajaran} ({$item->guru})",
                'start' => "{$item->tanggal}T{$item->jam_mulai}",
                'end'   => "{$item->tanggal}T{$item->jam_selesai}",
                'semester_group_id' => $item->semester_group_id, // group semester
            ];
        });

        return response()->json($events);
    }

    /** ===========================
     * Ambil jadwal berdasarkan tanggal
     * =========================== */
    public function getByTanggal($tanggal)
    {
        $jadwal = Jadwal::with('kelas')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($item) {
                return [
                    'id'           => $item->id,
                    'mata_pelajaran'=> $item->mata_pelajaran,
                    'guru'         => $item->guru,
                    'jam_mulai'    => $item->jam_mulai,
                    'jam_selesai'  => $item->jam_selesai,
                    'kelas_nama'   => $item->kelas->nama_kelas ?? '-',
                    'kelas_id'     => $item->kelas_id,
                    'tanggal'      => $item->tanggal,
                    'semester_group_id' => $item->semester_group_id,
                ];
            });

        return response()->json($jadwal);
    }

    /** ===========================
     * Store jadwal (sekali / 1 semester)
     * =========================== */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'kelas_id'       => 'required|exists:kelas,id',
                'mata_pelajaran' => 'required|string|max:100',
                'guru'           => 'required|string|max:100',
                'tanggal'        => 'required|date',
                'jam_mulai'      => 'required',
                'jam_selesai'    => 'required|after:jam_mulai',
                'ulang'          => 'required|in:sekali,semester',
            ]);

            // Normalisasi jam ke format H:i
            $request->merge([
                'jam_mulai'   => date('H:i', strtotime($request->jam_mulai)),
                'jam_selesai' => date('H:i', strtotime($request->jam_selesai)),
            ]);

            $tanggalMulai = Carbon::parse($request->tanggal);

            /** ============ SEKALI SAJA ============ */
            if ($request->ulang === 'sekali') {
                return $this->buatJadwal($request, $tanggalMulai, true, null);
            }

            /** ============ SEMESTER (16 pertemuan) ============ */

            $groupId      = uniqid("grp_"); // ID grup semester
            $maxPertemuan = 16;             // maksimal 16 kali pertemuan
            $createdCount = 0;

            for ($i = 0; $i < $maxPertemuan; $i++) {

                $result = $this->buatJadwal($request, $tanggalMulai, false, $groupId);

                // Jika pertemuan pertama bentrok → batalkan seluruh semester
                if ($result === false && $createdCount === 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jadwal minggu pertama bentrok. Tidak ada jadwal semester yang disimpan.',
                    ], 422);
                }

                // Minggu berikutnya bentrok → skip saja (lanjut minggu berikutnya)
                if ($result === true) {
                    $createdCount++;
                }

                // Naik 1 minggu (pertemuan berikutnya)
                $tanggalMulai->addWeek();
            }

            if ($createdCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal yang disimpan.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => "Jadwal semester berhasil disimpan ($createdCount pertemuan).",
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);

        } catch (Throwable $e) {

            Log::error("Gagal menyimpan jadwal: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Kesalahan server.',
            ], 500);
        }
    }

    /** ===========================
     * Helper: cek bentrok + simpan
     * =========================== */
    private function buatJadwal(Request $request, Carbon $tanggal, $returnResponse = true, $groupId = null)
    {
        $tanggalStr = $tanggal->format('Y-m-d');
        $mulai      = $request->jam_mulai;
        $selesai    = $request->jam_selesai;

        // Cek bentrok di hari itu, kelas yang sama
        $bentrok = Jadwal::where('kelas_id', $request->kelas_id)
            ->whereDate('tanggal', $tanggalStr)
            ->where(function ($q) use ($mulai, $selesai) {
                $q->where('jam_mulai', '<', $selesai)
                  ->where('jam_selesai', '>', $mulai);
            })
            ->exists();

        if ($bentrok) {
            if ($returnResponse) {
                return response()->json([
                    'success' => false,
                    'message' => "Jadwal bentrok pada tanggal $tanggalStr.",
                ], 422);
            }
            // untuk mode semester: kembalikan false (supaya loop tahu ini bentrok)
            return false;
        }

        // Simpan jadwal baru
        Jadwal::create([
            'kelas_id'          => $request->kelas_id,
            'mata_pelajaran'    => $request->mata_pelajaran,
            'guru'              => $request->guru,
            'tanggal'           => $tanggalStr,
            'jam_mulai'         => $mulai,
            'jam_selesai'       => $selesai,
            'semester_group_id' => $groupId,
        ]);

        if ($returnResponse) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil disimpan.',
            ]);
        }

        // untuk mode semester: kembalikan true (berhasil)
        return true;
    }

    /** ===========================
     * Update Jadwal
     * =========================== */
    public function update(Request $request, $id)
    {
        try {
            $jadwal = Jadwal::findOrFail($id);

            $request->validate([
                'mata_pelajaran' => 'required|string',
                'guru'           => 'required|string',
                'jam_mulai'      => 'required',
                'jam_selesai'    => 'required|after:jam_mulai',
            ]);

            $jadwal->update([
                'mata_pelajaran' => $request->mata_pelajaran,
                'guru'           => $request->guru,
                'jam_mulai'      => date('H:i', strtotime($request->jam_mulai)),
                'jam_selesai'    => date('H:i', strtotime($request->jam_selesai)),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui.',
            ]);

        } catch (Throwable $e) {

            Log::error("Gagal update jadwal: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Kesalahan server.',
            ], 500);
        }
    }

    /** ===========================
     * Hapus 1 jadwal
     * =========================== */
    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal dihapus.',
        ]);
    }

    /** ===========================
     * Hapus Semua Jadwal Semester
     * =========================== */
    public function deleteSemester($groupId)
    {
        Jadwal::where('semester_group_id', $groupId)->delete();

        return response()->json([
            'success' => true,
            'message' => "Semua jadwal semester dihapus.",
        ]);
    }
}
