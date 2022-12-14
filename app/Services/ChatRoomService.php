<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use Illuminate\Database\Eloquent\Collection;

class ChatRoomService
{
    public function index(ChatRoom $chatRoom)
    {
        $chatRoom = $this->loadChatRoomListOfChat($chatRoom);
        $chatRoom = $this->loadChatRoomMemberCount($chatRoom);
        $chatRoom = $this->loadUnreadChat($chatRoom);
        return $chatRoom;
    }

    public function list()
    {
        $chatRooms = $this->getListOfChatRoom();
        $chatRooms = $this->loadLastChatOfChatRoom($chatRooms);
        $chatRooms = $this->loadUnreadChats($chatRooms);
        return $chatRooms;
    }

    public function createDirectMessage(User $user): ChatRoom
    {
        $chatRoom = ChatRoom::create(
            [
                'name'                  =>  'Direct Message',
                'chat_room_type_id'     =>  1
            ]
        );
        $chatRoom->users()->attach(
            [
                auth()->id(),
                $user->id,
            ]
        );
        return $chatRoom;
    }

    public function getListOfChatRoom()
    {
        $user = auth()->id();
        $chatRooms = ChatRoom::query()
            ->whereRelation('users', 'users.id', $user)
            ->with(
                [
                    'users' =>  function ($query) use ($user) {
                        $query->where('users.id', '!=', $user);
                    }
                ]
            )
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
            );
            $chatRoom->lastChat = $chatRoom->chats[0];
            $chatRoom->unsetRelation('chats');
        });
        return $chatRooms;
    }

    public function loadUnreadChats(Collection $chatRooms)
    {
        $chatRooms->map(function ($chatRoom) {
            $chatRoom = $this->loadUnreadChat($chatRoom);
        });
        return $chatRooms;
    }

    public function loadUnreadChat($chatRoom)
    {
        $chatRoomUser = ChatRoomUser::query()
            ->where('chat_room_id', $chatRoom->id)
            ->where('user_id', auth()->id())
            ->first();
        $chatRoom->loadCount(
            [
                'chats as unread_chats' =>  function ($query) use ($chatRoomUser) {
                    $query->where('created_at', '>=', $chatRoomUser->last_opened);
                }
            ]
        );
        return $chatRoom;
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
