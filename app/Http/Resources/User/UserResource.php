<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ChatRoom\ChatRoomResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                            =>  $this->id,
            'name'                          =>  $this->name,
            'email'                         =>  $this->email,
            'last_seen'                     =>  $this->last_seen,
            'direct_message_room'           => $this->when(
                $this->directMessage != null,
                new ChatRoomResource($this->directMessage)
            )
        ];
    }
}
