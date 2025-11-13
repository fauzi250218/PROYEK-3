<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPerilaku extends Model
{
    use HasFactory;

    protected $table = 'catatan_perilakus';

    protected $fillable = [
        'murid_id',
        'kelas_id',
        'judul',
        'deskripsi',
        'kategori',
        'tanggal',
    ];

    public function murid()
    {
        return $this->belongsTo(Murid::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
