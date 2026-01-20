<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';

    protected $fillable = [
        'sesi_id',
        'murid_id',
        'jadwal_id',
        'status',
    ];

    /**
     * Relasi ke murid
     */
    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }

    /**
     * Relasi ke sesi
     */
    public function sesi()
    {
        return $this->belongsTo(Sesi::class, 'sesi_id');
    }

    /**
     * Relasi ke jadwal
     */
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
}
