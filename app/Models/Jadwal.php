<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'kelas_id',
        'nama_mapel',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
