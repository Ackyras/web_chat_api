<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function setHeaders()
    {
        $headers = [
            'Accept'        =>  'application/json',
            'Content-Type'  =>  'application/json'
        ];
        return $headers;
        $this->url = '/api/v1/auth/login';
    }
    public function setUrl()
    {
        $url = '/api/v1/auth/login';
        return $url;
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $user = [
            'email' =>  'user@user',
            'password'  =>  'password',
        ];
        $headers = $this->setHeaders();
        $url = $this->setUrl();
        $response = $this->json('POST', $url, $user, $headers)
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'message',
                    'access_token',
                    'token_type'
                ]
            );

        $response->assertStatus(200);
    }
}
