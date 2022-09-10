<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatRoom\ChatRoomResource;
use App\Http\Resources\User\UserResource;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();
        $chatRooms = ChatRoom::query()
            ->whereRelation('users', 'users.id', $user->id)
            ->with(
                [
                    'chats'    =>  function ($query) {
                        $query->orderBy('created_at', 'desc')
                            ->with(
                                [
                                    'user'
                                ]
                            )
                            ->first();
                    }
                ]
            )
            ->withCount(
                [
                    'chats as unread_chats' =>  function ($query) {
                        $query->where('created_at', '>', 'chat_room_user.last_opened');
                    }
                ]
            )
            ->get()
            //
        ;
        return response()->json(
            [
                'user'          =>  new UserResource($user),
                'chatRooms'     =>  ChatRoomResource::collection($chatRooms),
            ]
        );
    }
}
