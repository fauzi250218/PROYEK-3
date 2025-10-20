<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Nilai;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'jenis_kelamin',
        'nomer_whatsapp',
        'mata_pelajaran',
    ];

    /**
     * Relasi ke tabel users (akun login guru)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi 1:1 - Wali kelas dari satu kelas binaan
     */
    public function kelasBinaan()
    {
        return $this->hasOne(Kelas::class, 'guru_id');
    }

    /**
     * Relasi alias supaya kompatibel dengan kode lama
     * (agar "with(['user','kelas'])" di controller tidak error)
     */
    public function kelas()
    {
        return $this->kelasBinaan();
    }

    /**
     * Relasi 1:N - Guru bisa mengajar dan memberi banyak nilai ke siswa
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }
}
