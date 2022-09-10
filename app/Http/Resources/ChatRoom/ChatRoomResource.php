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
            'name'              =>  $this->name,
            'type'              =>  $this->type->name,
            'unread_count'      =>  $this->when(isset($this->unread_chats), $this->unread_chats),
            'member_count'      =>  $this->when(isset($this->users_count), $this->users_count),
            'chats'             =>  ChatResource::collection($this->whenLoaded('chats')),
            'last_chat'         =>  $this->when(isset($this->lastChat), new ChatResource($this->lastChat))
        ];
    }
}
