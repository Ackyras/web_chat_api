<?php

namespace Database\Seeders;

use App\Models\ChatRoomType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChatRoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $types = [
            'Direct Messsage',
            'Group',
        ];

        foreach ($types as $type) {
            ChatRoomType::create(
                [
                    'name'  =>  $type
                ]
            );
        }
    }
}
