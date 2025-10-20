<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama_kelas', 'deskripsi', 'guru_id'];

    /**
     * Relasi ke guru (wali kelas)
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Relasi ke murid-murid di kelas ini
     */
    public function murids()
    {
        return $this->hasMany(Murid::class, 'kelas_id');
    }

    /**
     * Relasi ke nilai siswa di kelas ini
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'kelas_id');
    }
}
