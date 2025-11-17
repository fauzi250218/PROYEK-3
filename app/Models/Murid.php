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
        'foto_profil',
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

    /**
     * ✅ Relasi ke data kehadiran
     */
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'murid_id');
    }

    /**
     * ✅ Relasi ke catatan perkembangan murid
     */
    public function catatan()
    {
        return $this->hasMany(CatatanPerilaku::class, 'murid_id');
    }

    // /**
    //  * ✅ Relasi ke laporan (opsional)
    //  */
    // public function laporan()
    // {
    //     return $this->hasMany(Laporan::class, 'murid_id');
    // }

    /**
     * ✅ Relasi ke notifikasi yang diterima murid ini
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'murid_id');
    }
}
