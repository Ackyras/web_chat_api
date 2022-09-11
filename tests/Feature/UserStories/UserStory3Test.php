<?php

namespace Tests\Feature\UserStories;

use Tests\TestCase;
use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserStory3Test extends TestCase
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

    public function testListAllMessagesFromSpecificUserWhoHasConversation()
    {
        $chatRoom = ChatRoom::query()
            ->where('chat_room_type_id', 1)
            ->has('users', 2)
            ->with('users')
            ->first();
        $headers = $this->setHeaders();
        $url = $this->setUrl('user/' . $chatRoom->users[1]->id);
        $response = $this->actingAs($chatRoom->users[0], 'sanctum')
            ->json('GET', $url, $headers)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'chatRoom'  => [
                        'id',
                        'name',
                        'type',
                        'chats' =>  [
                            [
                                'from',
                                'text'
                            ]
                        ]
                    ]
                ]
            );
    }
    public function testListAllMessagesFromSpecificUserWhoHasNoConversation()
    {
        $user1 = null;
        $user2 = null;
        do {
            $user1 = User::query()
                ->with('chatRooms')
                ->inRandomOrder()
                ->limit(1)
                ->first();
            $user2 = User::query()
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
        $headers = $this->setHeaders();
        $url = $this->setUrl('user/' . $user2->id);
        $response = $this->actingAs($user1, 'sanctum')
            ->json('GET', $url, $headers)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'user'  => [
                        'id',
                        'name',
                        'email',
                        'last_seen',
                    ]
                ]
            );
    }
}
