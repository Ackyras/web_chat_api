<?php

namespace Tests\Feature\UserStories;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserStory4Test extends TestCase
{
    public function setHeaders()
    {
        $headers = [
            'Accept'        =>  'application/json',
            'Content-Type'  =>  'application/json'
        ];
        return $headers;
    }

    public function setUrl(string $url = '')
    {
        $url = '/api/v1/chat/' . $url;
        return $url;
    }

    public function testListOfInvolvedConversations()
    {
        $user = User::with('chatRooms')->inRandomOrder()->first();

        $url = $this->setUrl();
        $headers = $this->setHeaders();

        if ($user->chatRooms) {
            $response = $this->actingAs($user, 'sanctum')
                ->json('GET', $url, $headers)
                ->assertStatus(200)
                ->assertJsonStructure(
                    [
                        'chatRooms'  => [
                            'id',
                            'name',
                            'member'    =>  [
                                [
                                    'id',
                                    'name',
                                    'email',
                                    'last_seen'
                                ]
                            ],
                            'type',
                            'unread_count',
                            'member_count',
                            'last_chat' =>  [
                                'from',
                                'text'
                            ]
                        ]
                    ]
                );
        } else {
            $response = $this->actingAs($user, 'sanctum')
                ->json('GET', $url, $headers)
                ->assertStatus(200)
                ->assertJsonStructure(
                    [
                        'chatRooms'
                    ]
                );
        }
    }
}
