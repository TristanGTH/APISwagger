<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_register()
    {
        $data = [
            'email' => 'test@test.com',
            'password' => 'test',
            'name' => 'Test'
        ];

        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(201);
    }

    public function test_login()
    {
        $data = [
            'email' => 'test@test.com',
            'password' => 'test'
        ];

        $response = $this->postJson('/api/auth/login', $data);

        $response->assertStatus(200);
    }
}
