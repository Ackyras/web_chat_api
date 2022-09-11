<?php

namespace Database\Factories;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'text'  =>  $this->faker->sentences(rand(1, 3), true)
        ];
    }

    public function groupChat(ChatRoom $chatRoom, User $user)
    {
        return $this->state(function (array $attributes) use ($chatRoom, $user) {
            return [
                'chat_room_id'  =>  $chatRoom->id,
                'user_id'       =>  $user->id
            ];
        });
    }
}
