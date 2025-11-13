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
        $angkatan = $request->angkatan;

        $jadwal = Jadwal::with('kelas')
            ->when($angkatan, function ($q) use ($angkatan) {
                $q->whereHas('kelas', function ($qq) use ($angkatan) {
                    $qq->where('angkatan', $angkatan);
                });
            })
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        // KIRIM DATA KOMPLIT agar JS tidak undefined
        $events = $jadwal->map(function ($item) {
            return [
                'id'             => $item->id,
                'guru'           => $item->guru,
                'mata_pelajaran' => $item->mata_pelajaran,
                'kelas_id'       => $item->kelas_id,
                'kelas_nama'     => $item->kelas->nama_kelas ?? '',
                'tanggal'        => $item->tanggal,
                'jam_mulai'      => $item->jam_mulai,
                'jam_selesai'    => $item->jam_selesai,

                // dipakai oleh kalender
                'title'          => "{$item->kelas->nama_kelas} - {$item->mata_pelajaran}",
                'start'          => "{$item->tanggal}T{$item->jam_mulai}",
                'end'            => "{$item->tanggal}T{$item->jam_selesai}",
            ];
        });

        return response()->json($events);
    }

    /** ===========================
     * Ambil jadwal berdasarkan tanggal
     * =========================== */
    public function getByTanggal($tanggal, Request $request)
    {
        $angkatan = $request->angkatan;

        $jadwal = Jadwal::with('kelas')
            ->when($angkatan, function ($q) use ($angkatan) {
                $q->whereHas('kelas', function ($qq) use ($angkatan) {
                    $qq->where('angkatan', $angkatan);
                });
            })
            ->whereDate('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($item) {
                return [
                    'id'             => $item->id,
                    'mata_pelajaran' => $item->mata_pelajaran,
                    'guru'           => $item->guru,
                    'jam_mulai'      => $item->jam_mulai,
                    'jam_selesai'    => $item->jam_selesai,
                    'kelas_nama'     => $item->kelas->nama_kelas ?? '-',
                    'kelas_id'       => $item->kelas_id,
                    'tanggal'        => $item->tanggal,
                ];
            });

        return response()->json($jadwal);
    }

    /** ===========================
     * Store jadwal (sekali / semester)
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

            // normalisasi jam
            $request->merge([
                'jam_mulai'   => date('H:i', strtotime($request->jam_mulai)),
                'jam_selesai' => date('H:i', strtotime($request->jam_selesai)),
            ]);

            $tanggalMulai = Carbon::parse($request->tanggal);

            // jika hanya sekali
            if ($request->ulang === 'sekali') {
                return $this->buatJadwal($request, $tanggalMulai, true);
            }

            // jika semester (mingguan 6 bulan)
            $tanggalAkhir = $tanggalMulai->copy()->addMonths(6);
            $createdCount = 0;

            while ($tanggalMulai <= $tanggalAkhir) {
                $result = $this->buatJadwal($request, $tanggalMulai, false);

                // minggu pertama bentrok → batalkan semua
                if ($result === false && $createdCount === 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Jadwal minggu pertama bentrok. Tidak ada yang disimpan.',
                    ], 422);
                }

                if ($result === true) {
                    $createdCount++;
                }

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
                'message' => "Jadwal berhasil disimpan ($createdCount kali).",
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
    private function buatJadwal(Request $request, Carbon $tanggal, $returnResponse = true)
    {
        $tanggalStr = $tanggal->format('Y-m-d');
        $mulai      = $request->jam_mulai;
        $selesai    = $request->jam_selesai;

        // cek bentrok
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
            return false;
        }

        Jadwal::create([
            'kelas_id'       => $request->kelas_id,
            'mata_pelajaran' => $request->mata_pelajaran,
            'guru'           => $request->guru,
            'tanggal'        => $tanggalStr,
            'jam_mulai'      => $mulai,
            'jam_selesai'    => $selesai,
        ]);

        if ($returnResponse) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil disimpan.',
            ]);
        }

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
     * Hapus Semua Jadwal 1 Mapel
     * =========================== */
    public function deleteSemester($mataPelajaran)
    {
        Jadwal::where('mata_pelajaran', $mataPelajaran)->delete();

        return response()->json([
            'success' => true,
            'message' => "Semua jadwal '$mataPelajaran' dihapus.",
        ]);
    }
}
