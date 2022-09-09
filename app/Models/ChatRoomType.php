<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function chatRooms()
    {
        return $this->hasMany(ChatRoom::class);
    }
}
