<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanSPP extends Model
{
    protected $table = 'tagihan_spp';

    protected $fillable = [
        'murid_id',
        'bulan',
        'tahun',
        'nominal',
        'status',
    ];

    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(PembayaranSPP::class, 'tagihan_id');
    }
}
