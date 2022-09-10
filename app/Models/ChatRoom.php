<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'chat_room_type_id',
    ];

    protected $with = [
        'type'
    ];

    public function type()
    {
        return $this->belongsTo(ChatRoomType::class, 'chat_room_type_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
