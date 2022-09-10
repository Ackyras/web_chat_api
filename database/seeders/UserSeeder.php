<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = [
            'name'      =>  'User',
            'email'     =>  'user@user',
            'password'  =>  bcrypt('password'),
        ];
        User::create($user);

        $user = [
            'name'      =>  'User2',
            'email'     =>  'user2@user',
            'password'  =>  bcrypt('password'),
        ];
        User::create($user);

        User::factory(10)->create();
    }
}
