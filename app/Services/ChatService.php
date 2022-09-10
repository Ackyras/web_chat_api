<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\ChatRoom;

class ChatService
{
    public function sendMessage(ChatRoom $chatRoom, array $validated)
    {
        if (!isset($validated['text'])) {
            return $this->nullChat();
        }
        return $this->storeChat($chatRoom, $validated);
    }

    public function storeChat(ChatRoom $chatRoom, array $validated)
    {
        $chat = Chat::make($validated);
        $chat->chat_room_id = $chatRoom->id;
        $chat->user_id = auth()->id();
        $chat->save();
        return $chat;
    }

    public function nullChat()
    {
        return null;
    }
}
