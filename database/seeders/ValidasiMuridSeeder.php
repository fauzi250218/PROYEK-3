<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ValidasiMurid;

class ValidasiMuridSeeder extends Seeder
{
    public function run()
    {
        $nisAwal = 2305020;
        $jumlahSiswa = 30;

        for ($i = 0; $i < $jumlahSiswa; $i++) {

            // Setengah ke kelas 1, setengah ke kelas 2
            $kelasId = ($i < $jumlahSiswa / 2) ? 1 : 2;

            ValidasiMurid::create([
                'nis' => (string)($nisAwal + $i),
                'kelas_id' => $kelasId,
            ]);
        }
    }
}
