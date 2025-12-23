<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranSPP extends Model
{
    protected $table = 'pembayaran_spp';

    protected $fillable = [
        'tagihan_id',
        'order_id',
        'nominal',
        'gross_amount',
        'snap_token',
        'payment_type',
        'transaction_status',
    ];

    public function tagihan()
    {
        return $this->belongsTo(TagihanSPP::class, 'tagihan_id');
    }
}
