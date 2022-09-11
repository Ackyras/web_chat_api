<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = User::find(1);
        $chatRooms = ChatRoom::factory(5)
            ->groupType()
            ->hasAttached($user)
            ->create();
        foreach ($chatRooms as $chatRoom) {
            $users = User::where('id', '!=', $user->id)->where('id', '!=', 2)->inRandomOrder()->limit(rand(2, 5))->get();
            $chatRoom->users()->attach($users);
            for ($i = 0; $i < rand(15, 20); $i++) {
                # code...
                $chats = Chat::factory()
                    ->groupChat($chatRoom, $users->random())
                    ->create();
            }
        }
        $chatRooms = ChatRoom::factory(5)
            ->groupType()
            ->create();
        foreach ($chatRooms as $chatRoom) {
            $users = User::where('id', '!=', $user->id)->where('id', '!=', 2)->inRandomOrder()->limit(rand(2, 5))->get();
            $chatRoom->users()->attach($users);
            for ($i = 0; $i < rand(15, 20); $i++) {
                # code...
                $chats = Chat::factory()
                    ->groupChat($chatRoom, $users->random())
                    ->create();
            }
        }
        $chatRooms = ChatRoom::factory(5)
            ->directMessageType()
            ->create();
        foreach ($chatRooms as $chatRoom) {
            $user1 = null;
            $user2 = null;
            do {
                $user1 = User::query()
                    ->where('id', '!=', $user->id)->where('id', '!=', 2)
                    ->with('chatRooms')
                    ->inRandomOrder()
                    ->limit(1)
                    ->first();
                $user2 = User::query()
                    ->where('id', '!=', $user->id)
                    ->where('id', '!=', 2)
                    ->where('id', '!=', $user1->id)
                    ->inRandomOrder()
                    ->limit(1)
                    ->first();
            } while (
                ChatRoom::query()
                ->where('chat_room_type_id', 1)
                ->whereRelation('users', 'users.id', $user1)
                ->whereRelation('users', 'users.id', $user2)
                ->exists()
            );
            $chatRoom->users()->attach([$user1->id, $user2->id]);
            for ($i = 0; $i < rand(15, 20); $i++) {
                # code...
                $chats = Chat::factory()
                    ->groupChat($chatRoom, $users->random())
                    ->create();
            }
        }
    }
}
