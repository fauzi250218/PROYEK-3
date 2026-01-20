<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $table = 'sesi';

    protected $fillable = [
        'kelas_id',
        'jadwal_id',
        'judul_sesi',
        'topik',
        'deskripsi',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function modul()
    {
        return $this->hasMany(Modul::class);
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class);
    }
}
