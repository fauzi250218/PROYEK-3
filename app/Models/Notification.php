<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'murid_id',
        'title',
        'message',
        'is_read',
    ];

    public function murid()
    {
        return $this->belongsTo(Murid::class, 'murid_id');
    }
}
