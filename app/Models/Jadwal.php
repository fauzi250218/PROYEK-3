<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwals';

    protected $fillable = [
        'group_id',
        'kelas_id',
        'mata_pelajaran',
        'guru',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
        'keterangan',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
