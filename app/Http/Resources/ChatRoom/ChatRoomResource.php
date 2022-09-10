<?php

namespace App\Http\Resources\ChatRoom;

use App\Http\Resources\Chat\ChatResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                =>  $this->id,
            'name'              =>  $this->when(
                $this->type->id == 2,
                $this->name,
                $this->users()->where('chat_room_user.user_id', '!=', auth()->id())->first()->name . '(DM)'
            ),
            'type'              =>  $this->type->name,
            'unread_count'      =>  $this->when(isset($this->unread_chats), $this->unread_chats),
            'member_count'      =>  $this->when(isset($this->users_count), $this->users_count),
            'chats'             =>  ChatResource::collection($this->whenLoaded('chats')),
            'last_chat'         =>  $this->when(isset($this->lastChat), new ChatResource($this->lastChat))
        ];
    }
}
