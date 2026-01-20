<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perkembangan extends Model
{
    use HasFactory;

    // 🚨 WAJIB ADA BARIS INI!
    protected $table = 'perkembangan';

    protected $fillable = [
        'guru_id',
        'murid_id',
        'aspek',
        'catatan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }
}
