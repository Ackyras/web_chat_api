<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatRoom;

class UserService
{
    public function list()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $users->map(function ($user) {
            $user->directMessage = $this->loadUserDirectMessage($user);
        });
        return $users;
    }

    public function isDirectMessageExists(User $user)
    {
        $chatRoom = $this->loadUserDirectMessage($user);
        return $chatRoom != null ? true : false;
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
        return $chatRoom;
        // return $user;
    }
}
