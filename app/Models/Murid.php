<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    use HasFactory;

    protected $table = 'murids';

    protected $fillable = [
        'nis',
        'nama',
        'email',
        'kelas_id',
        'jenis_kelamin',
        'kata_sandi',
        'nomer_whatsapp',
    ];

    /**
     * Relasi ke kelas tempat murid ini belajar
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke nilai-nilai milik murid ini
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'murid_id');
    }
}
