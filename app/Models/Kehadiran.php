<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadirans';

    protected $fillable = [
        'murid_id',
        'tanggal',
        'status',
        'keterangan',
    ];

    /**
     * Relasi ke model Murid
     */
    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }
}
