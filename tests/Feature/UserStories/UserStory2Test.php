<?php

namespace Tests\Feature\UserStories;

use App\Models\ChatRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserStory2Test extends TestCase
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

    public function testReplyToExistingChatRoom()
    {
        $chatRoom = ChatRoom::query()
            ->where('chat_room_type_id', 1)
            ->has('users', 2)
            ->with('users')
            ->first();
        $body = [
            'text'  =>  'Holla',
        ];
        $url = $this->setUrl('room/' . $chatRoom->id);
        $headers = $this->setHeaders();
        $response = $this->actingAs($chatRoom->users[0], 'sanctum')
            ->json('POST', $url, $body, $headers)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'chatRoom' =>  [
                        'id',
                        'name',
                        'type',
                    ],
                    'sent_message'  =>  [
                        'from',
                        'text'
                    ]
                ]
            );
        $senderResponse = $this->actingAs($chatRoom->users[0], 'sanctum')
            ->json('GET', $url, $headers)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'chatRoom' =>  [
                        'id',
                        'name',
                        'type',
                        'unread_count',
                        'member_count',
                        'chats' =>  [
                            [
                                'from',
                                'text'
                            ]
                        ]
                    ]
                ]
            );
        $receiverResponse = $this->actingAs($chatRoom->users[1], 'sanctum')
            ->json('GET', $url, $headers)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'chatRoom' =>  [
                        'id',
                        'name',
                        'type',
                        'unread_count',
                        'member_count',
                        'chats' =>  [
                            [
                                'from',
                                'text'
                            ]
                        ]
                    ]
                ]
            );
        $this->assertTrue(
            $senderResponse['chatRoom']['chats'] == $receiverResponse['chatRoom']['chats']
        );
    }
}
