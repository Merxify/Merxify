<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can authenticate users', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertStatus(200)
        ->assertExactJsonStructure([
            'data' => [
                'user',
                'access_token',
                'token_type',
            ],
            'message',
            'status',
        ])
        ->assertJsonPath('data.user.email', $user->email)
        ->assertJsonPath('data.user.first_name', $user->first_name)
        ->assertJsonPath('data.token_type', 'Bearer')
        ->assertJsonPath('message', 'Login successful.')
        ->assertJsonPath('status', 200);
});

it('can not authenticate users with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response
        ->assertStatus(401)
        ->assertExactJsonStructure([
            'message',
            'errors',
        ])
        ->assertJsonPath('message', 'Invalid credentials.')
        ->assertJsonPath('errors', null);
});

it('can not authenticate users with inactive account', function () {
    $user = User::factory()->create(['is_active' => false]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertStatus(403)
        ->assertExactJsonStructure([
            'message',
            'errors',
        ])
        ->assertJsonPath('message', 'Account is inactive.')
        ->assertJsonPath('errors', null);
});

it('can logout users', function () {
    Sanctum::actingAs(
        User::factory()->create(),
        ['*'],
    );

    $response = $this->postJson('/api/v1/auth/logout');

    $response
        ->assertStatus(200)
        ->assertExactJsonStructure([
            'data',
            'message',
            'status',
        ])
        ->assertJsonPath('data', null)
        ->assertJsonPath('message', 'Logged out successfully')
        ->assertJsonPath('status', 200);
});
