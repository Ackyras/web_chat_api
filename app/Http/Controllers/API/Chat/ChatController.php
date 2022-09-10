<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendingChatRequests;
use App\Http\Resources\ChatRoom\ChatRoomResource;
use App\Http\Resources\User\UserResource;
use App\Models\ChatRoom;
use App\Models\User;
use App\Services\ChatService;
use App\Services\ChatRoomService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function index(ChatRoomService $chatRoomService)
    {
        $chatRooms = $chatRoomService->list();
        return response()->json(
            [
                'chatRooms'     =>  ChatRoomResource::collection($chatRooms),
            ]
        );
    }
}
