<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
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
            ->get()
            //
        ;
        return response()->json(
            [
                'user'          =>  new UserResource($user),
                'chatRooms'     =>  $chatRooms
            ]
        );
    }
}
