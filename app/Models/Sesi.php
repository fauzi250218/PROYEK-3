<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $table = 'sesi';

    protected $fillable = [
        'kelas_id',
        'judul_sesi',
        'topik',
        'deskripsi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    // Relasi ke Jadwal berdasarkan kelas & tanggal
    public function jadwal()
    {
        return $this->hasOne(Jadwal::class, 'kelas_id', 'kelas_id')
                    ->whereDate('tanggal', $this->tanggal);
    }
}
