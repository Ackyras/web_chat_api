<?php

namespace Tests\Feature\UserStories;

use Tests\TestCase;
use App\Models\Chat;
use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class UserStory1Test extends TestCase
{
    // use RefreshDatabase;
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendingMessageToNewUser()
    {
        $user = User::find(1);
        $user2 = User::query()
            ->whereDoesntHave(
                'chatRooms',
                function ($query) use ($user) {
                    $query->where('chat_room_user.user_id', $user->id);
                }
            )
            ->inRandomOrder()
            ->first();
        $body = [
            'text'  =>  'Holla',
        ];
        $url = $this->setUrl('user/' . $user2->id);
        $headers = $this->setHeaders();
        $response = $this->actingAs($user, 'sanctum')
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
        $chatRoom = $response['chatRoom'];
        $chat = $response['sent_message'];
        // Check if chat Room created
        $this->assertDatabaseHas(
            'chat_rooms',
            [
                'id'                    =>  $chatRoom['id'],
                'chat_room_type_id'     =>  1,
            ]
        );
        // Check if message is sent
        $this->assertDatabaseHas(
            'chats',
            [
                'chat_room_id'      =>  $chatRoom['id'],
                'text'              =>  $chat['text'],
                'user_id'           =>  $user->id,
            ]
        );
        $url = $this->setUrl('room/' . $chatRoom['id']);
        $response = $this->actingAs($user2, 'sanctum')
            ->json('GET', $url, $headers)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'chatRoom'  =>  [
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
            $response['chatRoom']['chats'][count($response['chatRoom']['chats']) - 1]['text'] == $body['text']
        );
    }
    public function testSendingNullMessageToNewUser()
    {
        $user = User::find(1);
        $user2 = User::query()
            ->whereDoesntHave(
                'chatRooms',
                function ($query) use ($user) {
                    $query->where('chat_room_user.user_id', $user->id);
                }
            )
            ->inRandomOrder()
            ->first();
        $body = [
            'text'  =>  null,
        ];
        $chatRoomsCount = ChatRoom::all()->count();
        $chatsCount = Chat::all()->count();
        $url = $this->setUrl('user/' . $user2->id);
        $headers = $this->setHeaders();
        $response = $this->actingAs($user, 'sanctum')
            ->json('POST', $url, $body, $headers)
            ->assertStatus(200)
            ->assertJson(
                [
                    'chatRoom'  =>  null,
                    'sent_message'  =>  null
                ]
            );
        // Check if chat Room is not created
        $this->assertDatabaseCount(
            'chat_rooms',
            $chatRoomsCount

        );
        // Check if message is not sent
        $this->assertDatabaseCount(
            'chats',
            $chatsCount
        );
    }
}
