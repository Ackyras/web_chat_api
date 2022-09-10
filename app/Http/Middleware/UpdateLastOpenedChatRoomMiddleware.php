<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ChatRoomUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UpdateLastOpenedChatRoomMiddleware
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
        $response = $next($request);
        if (auth()->check()) {
            $chatRoom = $request->route('chatRoom');
            $expiresAt = Carbon::now()->addMinute(1);
            Cache::put('chat-room-is-opened-' . auth()->id(), true, $expiresAt);

            $chatRoomUser = ChatRoomUser::where('chat_room_id', $chatRoom->id)->where('user_id', auth()->id())->first();
            $chatRoomUser->update(
                [
                    'last_opened'   =>  Carbon::now(),
                ]
            );
        }
        return $response;
    }
}
