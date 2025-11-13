<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Murid;
use App\Models\Perkembangan;
use App\Models\Nilai;
use App\Models\Kelas;

class GuruDashboardController extends Controller
{
    public function index()
    {
        // Pastikan guru terautentikasi
        $guru = Auth::user()->guru ?? null;

        if (!$guru) {
            abort(403, 'Akses ditolak: Guru tidak ditemukan.');
        }

        // Ambil kelas binaan guru (kalau ada)
        $kelas = Kelas::where('guru_id', $guru->id)->first();

        // Hitung total murid di kelas binaan
        $jumlahMuridKelas = $kelas ? $kelas->murids()->count() : 0;

        // Hitung total laporan perkembangan yang dibuat guru
        $jumlahPerkembangan = Perkembangan::where('guru_id', $guru->id)->count();

        // Hitung total nilai yang sudah diinput oleh guru
        $jumlahNilai = Nilai::where('guru_id', $guru->id)->count();

        // ==============================
        // 📊 Grafik Jumlah Laki-Laki & Perempuan per Bulan
        // ==============================

        // Pastikan hanya murid dari kelas binaan guru yang diambil
        $muridQuery = $kelas ? Murid::where('kelas_id', $kelas->id) : Murid::query();

        // Ambil data jumlah murid laki-laki dan perempuan per bulan (berdasarkan tanggal dibuat)
        $muridLaki = $muridQuery->where('jenis_kelamin', 'L')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $muridPerempuan = $muridQuery->where('jenis_kelamin', 'P')
            ->select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // ==============================
        // 📈 Grafik Jumlah Nilai Diinput per Bulan
        // ==============================
        $nilaiPerBulan = Nilai::select(DB::raw('MONTH(created_at) as bulan'), DB::raw('COUNT(*) as total'))
            ->where('guru_id', $guru->id)
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // ==============================
        // 🔖 Bentuk Label Bulan & Data per Bulan (Jan - Des)
        // ==============================
        $labelsBulan = [];
        $dataLaki = [];
        $dataPerempuan = [];
        $dataNilai = [];

        for ($i = 1; $i <= 12; $i++) {
            $labelsBulan[] = date('F', mktime(0, 0, 0, $i, 1)); // Nama bulan
            $dataLaki[] = $muridLaki[$i] ?? 0;
            $dataPerempuan[] = $muridPerempuan[$i] ?? 0;
            $dataNilai[] = $nilaiPerBulan[$i] ?? 0;
        }

        // ==============================
        // 🚀 Kirim semua data ke view
        // ==============================
        return view('guru.dashboard.index', [
            'jumlahMuridKelas' => $jumlahMuridKelas,
            'jumlahPerkembangan' => $jumlahPerkembangan,
            'jumlahNilai' => $jumlahNilai,
            'labelsBulan' => $labelsBulan,
            'dataLaki' => $dataLaki,
            'dataPerempuan' => $dataPerempuan,
            'dataNilai' => $dataNilai,
        ]);
    }
}
