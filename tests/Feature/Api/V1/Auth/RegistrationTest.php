<?php

use App\Models\User;

test('new users can register', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'token' => $response->json('data.token'),
            ],
            'message' => 'Registered.',
            'status' => 200,
        ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

test('new users cannot register with same email', function () {
    User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertJson([
            'message' => 'The email has already been taken.',
            'errors' => [
                'email' => [
                    'The email has already been taken.',
                ],
            ],
        ]);

    $this->assertDatabaseMissing('users', [
        'name' => 'Test User',
    ]);
});
