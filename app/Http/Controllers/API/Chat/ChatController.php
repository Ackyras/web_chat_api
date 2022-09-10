<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\SendingChatRequests;
use App\Http\Resources\ChatRoom\ChatRoomResource;
use App\Http\Resources\User\UserResource;
use App\Models\ChatRoom;
use App\Models\User;
use App\Services\ChatService;
use App\Services\GroupChatService;
use App\Services\UserService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function index(GroupChatService $groupChatService)
    {
        $user = auth()->user();
        $chatRooms = $groupChatService->list();
        return response()->json(
            [
                'user'          =>  new UserResource($user),
                'chatRooms'     =>  ChatRoomResource::collection($chatRooms),
            ]
        );
    }

    public function user(UserService $userService)
    {
        $users = $userService->index();
        return response()->json(
            [
                'users' =>  UserResource::collection($users),
            ]
        );
    }

    public function sendToUser(SendingChatRequests $request, UserService $userService, User $user)
    {
        $validated = $request->validated();
        $chat = null;
        if ($userService->checkIfChatRoomIsAvailable($user)) {
        } else {
        }

        return response()->json(
            $userService->checkIfChatRoomIsAvailable($user)
        );
        $chat = $userService->sendMessage($user, $validated);
    }
}
