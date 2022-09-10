<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatRoom;

class UserService
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $users->map(function ($user) {
            $user = $this->loadUserDirectMessage($user);
        });
        return $users;
    }

    public function sendMessage(User $user, array $validated)
    {
        $chat = null;

        return $this->storeChat($user, $validated);
    }

    public function checkIfChatRoomIsAvailable(User $user)
    {
        $user = $this->loadUserDirectMessage($user);
        return $user->directMessage != null ? true : false;
    }

    public function sendMessageToExistingChatRoom(User $user, array $validated)
    {
        
    }

    public function storeChat(User $user, array $validated)
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

    public function loadUserDirectMessage($user)
    {
        $chatRoom = ChatRoom::query()
            ->has('users', 2)
            ->whereRelation('users', 'chat_room_user.user_id', $user->id)
            ->whereRelation('users', 'chat_room_user.user_id', auth()->id())
            ->first();
        $user->directMessage = $chatRoom;
        return $user;
    }
}
