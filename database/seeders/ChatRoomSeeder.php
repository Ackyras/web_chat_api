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
        $chatRooms = ChatRoom::factory(3)
            ->groupType()
            ->hasAttached($user)
            ->create();
        foreach ($chatRooms as $chatRoom) {
            $users = User::inRandomOrder()->limit(rand(2, 5))->get();
            $chatRoom->users()->attach($users);
            for ($i = 0; $i < rand(15, 20); $i++) {
                # code...
                $chats = Chat::factory()
                    ->groupChat($chatRoom, $users->random())
                    ->create();
            }
        }
    }
}
