<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_one_role',
        'user_two_id',
        'user_two_role'
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function lastMessage() {
    return $this->hasOne(ChatMessage::class, 'chat_room_id')->latest();
    }

    // Tentukan lawan chat
    public function peer() {
        return $this->belongsTo(User::class, 'user_two_id'); // contoh untuk murid/guru
    }

}
