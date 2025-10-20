<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'murid_id',
        'guru_id',
        'kelas_id',
        'mata_pelajaran',
        'tugas',
        'ulangan_harian',
        'uts',
        'uas'
    ];

    /**
     * Relasi ke murid yang memiliki nilai ini
     */
    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }

    /**
     * Relasi ke guru yang memberi nilai
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Relasi ke kelas tempat nilai ini diberikan
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
