<?php

use App\Models\User;

it('can register new users', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'John Doe',
        'email' => 'john@merxify.com',
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
        'name' => 'John Doe',
        'email' => 'john@merxify.com',
    ]);
});

it('cannot register new users with same email', function () {
    User::factory()->create([
        'email' => 'john@merxify.com',
    ]);

    $response = $this->postJson('/api/v1/auth/register', [
        'name' => 'John Doe',
        'email' => 'john@merxify.com',
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
        'name' => 'John Doe',
    ]);
});
