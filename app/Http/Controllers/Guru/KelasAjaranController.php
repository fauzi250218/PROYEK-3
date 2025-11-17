<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;

class KelasAjaranController extends Controller
{
    public function index()
    {
        // DATA KELAS AJARAN (dummy)
        $kelasAjaran = [
            [
                'id' => 1,
                'kelas' => 'Kelas 7A',
                'mapel' => 'Matematika',
                'guru' => 'Bu Melati'
            ],
            [
                'id' => 2,
                'kelas' => 'Kelas 8C',
                'mapel' => 'IPA',
                'guru' => 'Pak Budi'
            ],
            [
                'id' => 3,
                'kelas' => 'Kelas 9B',
                'mapel' => 'Bahasa Inggris',
                'guru' => 'Bu Sinta'
            ],
        ];

        return view('guru.manajemen-kelas.kelas-ajaran.index', compact('kelasAjaran'));
    }

    public function detail($id)
    {
        // DATA DETAIL KELAS
        $data = [
            1 => [
                'nama_kelas' => 'Kelas 7A',
                'mapel' => 'Matematika',
                'wali_kelas' => 'Bu Melati',
                'guru' => 'Bu Melati',
                'jumlah_siswa' => 32
            ],

            2 => [
                'nama_kelas' => 'Kelas 8C',
                'mapel' => 'IPA',
                'wali_kelas' => 'Pak Budi',
                'guru' => 'Pak Budi',
                'jumlah_siswa' => 30
            ],

            3 => [
                'nama_kelas' => 'Kelas 9B',
                'mapel' => 'Bahasa Inggris',
                'wali_kelas' => 'Bu Sinta',
                'guru' => 'Bu Sinta',
                'jumlah_siswa' => 29
            ],
        ];

        // Jika ID tidak ditemukan → 404
        if (!isset($data[$id])) {
            abort(404, "Kelas tidak ditemukan");
        }

        $detail = $data[$id];

        return view('guru.manajemen-kelas.kelas-ajaran.detail-kelas', compact('detail'));
    }
}
