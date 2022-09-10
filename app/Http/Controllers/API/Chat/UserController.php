<?php

namespace App\Http\Controllers\API\Chat;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\Chat\SendingChatRequests;
use App\Http\Resources\Chat\ChatResource;
use App\Http\Resources\ChatRoom\ChatRoomResource;
use App\Services\ChatService;
use App\Services\ChatRoomService;

class UserController extends Controller
{
    //
    public function list(UserService $userService)
    {
        $users = $userService->list();
        return response()->json(
            [
                // 'users' =>  $users,
                'users' =>  UserResource::collection($users),
            ]
        );
    }

    public function show(UserService $userService, User $user)
    {
        if ($userService->isDirectMessageExists($user)) {
            $chatRoom = $userService->loadUserDirectMessage($user);
            $chatRoom->load('chats');
            return response()->json(
                [
                    'chatRoom'  =>  new ChatRoomResource($chatRoom),
                ]
            );
        } else {
            return response()->json(
                [
                    'user'  => new UserResource($user),
                ]
            );
        }
    }

    public function send(SendingChatRequests $request, UserService $userService, ChatRoomService $chatRoomService, ChatService $chatService, User $user)
    {
        $validated = $request->validated();
        $chat = null;
        $chatRoom = null;
        if ($chatService->isTextIsEmpty($validated)) {
            $chat = $chatService->nullChat();
        } else {
            if ($userService->isDirectMessageExists($user)) {
                $chatRoom = $userService->loadUserDirectMessage($user);
            } else {
                $chatRoom = $chatRoomService->createDirectMessage($user);
            }
            $chat = $chatService->sendMessage($chatRoom, $validated);
        }
        return response()->json(
            [
                'chatRoom'          =>  $chatRoom == null ? null : new ChatRoomResource($chatRoom),
                'sent_message'      =>  $chat == null ? null : new ChatResource($chat)
            ]
        );
    }
}
