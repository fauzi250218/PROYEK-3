<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidasiMurid extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'kelas_id',
    ];

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke murid (one-to-one karena nis unik)
    public function murid()
    {
        return $this->hasOne(Murid::class, 'nis', 'nis');
    }
}
