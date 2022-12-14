<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendingChatRequests;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\ChatRoom\ChatRoomResource;
use App\Models\ChatRoom;
use App\Services\ChatService;
use App\Services\ChatRoomService;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    //
    public function index(ChatRoom $chatRoom, ChatRoomService $chatRoomService)
    {
        $chatRoom = $chatRoomService->index($chatRoom);
        return response()->json(
            [
                'chatRoom'  =>  new ChatRoomResource($chatRoom),
            ]
        );
    }

    public function send(SendingChatRequests $request, ChatRoom $chatRoom, ChatService $ChatService)
    {
        $validated = $request->validated();
        $chat = null;
        if ($ChatService->isTextIsEmpty($validated)) {
            $chat = $ChatService->nullChat();
        } else {
            $chat = $ChatService->sendMessage($chatRoom, $validated);
        }
        return response()->json(
            // $chat
            [
                'chatRoom'      =>  new ChatRoomResource($chatRoom),
                'sent_message'  =>  isset($validated['text']) ?  new ChatResource($chat) : $chat,
            ]
        );
    }
}
