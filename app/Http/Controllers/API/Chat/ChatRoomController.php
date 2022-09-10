<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatRoom\ChatRoomResource;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    //
    public function index(ChatRoom $chatRoom)
    {
        $chatRoom->load(
            [
                'chats' => [
                    'user'
                ]
            ],
        )->loadCount(
            [
                'users',
                'chats as unread_chats' =>  function ($query) {
                    $query->where('created_at', '>', 'chat_room_user.last_opened');
                }
            ]
        );

        return response()->json(
            [
                'chatRoom'  =>  new ChatRoomResource($chatRoom),
            ]
        );
    }

    public function send(Request $request, ChatRoom $chatRoom)
    {
        return $request->all();
    }
}
