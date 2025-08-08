<?php

it('can register new users', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'token' => $response->json('data.token'),
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
    ]);
});

it('cannot register new users with same email', function () {
    $user = \App\Models\User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertJson([
            'success' => false,
            'message' => 'Validation Error.',
            'data' => [
                'email' => [
                    'The email has already been taken.',
                ],
            ],
        ]);

    $this->assertDatabaseMissing('users', [
        'name' => 'Test User',
    ]);
});
