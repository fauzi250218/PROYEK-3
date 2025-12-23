<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Murid extends Authenticatable
{
    use HasFactory, HasApiTokens;

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
     * Sembunyikan field sensitif
     */
    protected $hidden = [
        'kata_sandi',
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
     * Relasi ke data kehadiran
     */
    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'murid_id');
    }

    /**
     * Relasi ke catatan perilaku (lama)
     */
    public function catatan()
    {
        return $this->hasMany(CatatanPerilaku::class, 'murid_id');
    }

    /**
     * 🔥 Relasi ke catatan perkembangan (baru)
     */
    public function catatanPerkembangan()
    {
        return $this->hasMany(CatatanPerkembangan::class, 'murid_id');
    }

    /**
     * Relasi ke notifikasi yang diterima murid ini
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'murid_id');
    }

    /**
     * Relasi ke Tagihan SPP
     */
    public function tagihanSPP()
    {
        return $this->hasMany(TagihanSPP::class, 'murid_id');
    }
}
