<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
    protected $table = 'modul';

    protected $fillable = [
        'kelas_id',
        'sesi_id',
        'judul',
        'file',
        'topik',
        'catatan'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function sesi()
    {
        return $this->belongsTo(Sesi::class);
    }
}
