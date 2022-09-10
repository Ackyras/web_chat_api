<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatRoom;

class ChatRoomService
{
    public function index(ChatRoom $chatRoom)
    {
        $chatRoom = $this->loadChatRoomListOfChat($chatRoom);
        $chatRoom = $this->loadChatRoomMemberCount($chatRoom);
        return $chatRoom;
    }

    public function list()
    {
        $chatRooms = $this->getListOfChatRoom();
        $chatRooms = $this->loadLastChatOfChatRoom($chatRooms);
        return $chatRooms;
    }

    public function createDirectMessage(User $user)
    {
        $chatRoom = ChatRoom::create(
            [
                'name'                  =>  'Direct Message',
                'chat_room_type_id'     =>  1
            ]
        );
    }

    public function getListOfChatRoom()
    {
        $user = auth()->id();
        $chatRooms = ChatRoom::query()
            ->whereRelation('users', 'users.id', $user)
            ->withCount(
                [
                    'users'
                ]
            )
            ->get()
            //
        ;
        return $chatRooms;
    }

    public function loadLastChatOfChatRoom($chatRooms)
    {
        $chatRooms->map(function ($chatRoom) {
            $chatRoom->load(
                [
                    'chats'    =>  function ($query) {
                        $query->orderBy('created_at', 'desc')
                            ->with(
                                [
                                    'user'
                                ]
                            )
                            ->limit(1);
                    }
                ]
            )->loadCount(
                [
                    'chats as unread_chats' =>  function ($query) {
                        $query->where('created_at', '>=', 'chat_room_user.last_opened');
                    }
                ]
            );
            $chatRoom->lastChat = $chatRoom->chats[0];
            $chatRoom->unsetRelation('chats');
        });
        return $chatRooms;
    }

    public function loadChatRoomListOfChat(ChatRoom $chatRoom)
    {
        $chatRoom->load(
            [
                'chats' => [
                    'user'
                ]
            ],
        );
        return $chatRoom;
    }

    public function loadChatRoomMemberCount(ChatRoom $chatRoom)
    {
        $chatRoom->loadCount(
            [
                'users',
                'chats as unread_chats' =>  function ($query) {
                    $query->where('created_at', '>', 'chat_room_user.last_opened');
                }
            ]
        );
        return $chatRoom;
    }
}
