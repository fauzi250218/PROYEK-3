<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $kelas = [
            [
                'nama_kelas' => 'Kelas A',
                'wali_kelas' => null,
                'deskripsi' => 'Kelas tingkat A untuk siswa awal.',
            ],
            [
                'nama_kelas' => 'Kelas B',
                'wali_kelas' => null,
                'deskripsi' => 'Kelas tingkat B untuk siswa lanjutan.',
            ],
        ];

        foreach ($kelas as $data) {
            Kelas::create($data);
        }
    }
}
