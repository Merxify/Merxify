<?php

use App\Models\User;

it('can register new users', function () {
    $response = $this->postJson('/api/v1/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@merxify.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(201)
        ->assertExactJsonStructure([
            'data' => [
                'user',
                'access_token',
                'token_type',
            ],
            'message',
            'status',
        ])
        ->assertJsonPath('data.user.email', 'john@merxify.com')
        ->assertJsonPath('data.user.first_name', 'John')
        ->assertJsonPath('data.token_type', 'Bearer')
        ->assertJsonPath('message', 'User registered successfully')
        ->assertJsonPath('status', 201);

    $this->assertDatabaseHas('users', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@merxify.com',
    ]);
});

it('cannot register new users with same email', function () {
    User::factory()->create([
        'email' => 'john@merxify.com',
    ]);

    $response = $this->postJson('/api/v1/auth/register', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@merxify.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response
        ->assertStatus(422)
        ->assertExactJsonStructure([
            'message',
            'errors',
        ])
        ->assertJsonPath('message', 'This email is already registered')
        ->assertJsonPath('errors.email', ['This email is already registered']);

    $this->assertDatabaseMissing('users', [
        'first_name' => 'John',
        'last_name' => 'Doe',
    ]);
});
