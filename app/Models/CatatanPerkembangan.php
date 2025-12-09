<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPerkembangan extends Model
{
    use HasFactory;

    protected $table = 'catatan_perkembangan';

    protected $fillable = [
        'murid_id',
        'guru_id',
        'kategori',
        'catatan',
        'tanggal',
    ];

    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
