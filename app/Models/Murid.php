<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    protected $table = 'murids';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nis',
        'nama',
        'email',
        'kelas',
        'jenis_kelamin',
        'kata_sandi',
        'nomer_whatsapp',
    ];

    protected $hidden = ['kata_sandi']; // biar password tidak ikut ditampilkan
}
