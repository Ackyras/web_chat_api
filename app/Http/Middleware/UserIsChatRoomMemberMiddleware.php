<?php

namespace App\Http\Middleware;

use App\Models\ChatRoom;
use Closure;
use Illuminate\Http\Request;

class UserIsChatRoomMemberMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $chatRoom = $request->route('chatRoom');
        $chatRoom->load(
            [
                'type',
                'users'
            ]
        );
        if (!$chatRoom->users->where('id', auth()->id())->first()) {
            return response()->json(
                [
                    'msg'   =>  'You are not the member of this chat room'
                ]
            );
        }
        return $next($request);
    }
}
