<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\Sanctum;

test('users can authenticate', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();

    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            'token',
        ],
        'message',
        'status',
    ]);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();

    $response->assertStatus(422);

    $response->assertExactJson([
        'message' => 'These credentials do not match our records.',
        'errors' => [
            'email' => [
                'These credentials do not match our records.',
            ],
        ],
    ]);
});

test('users can logout', function () {
    $user = Sanctum::actingAs(
        User::factory()->create(),
        ['*'],
    );

    $response = $this->postJson('/api/logout');

    $response->assertOk();

    $this->assertEmpty($user->tokens);
});

test('users are rate limited', function () {
    $user = User::factory()->create();

    RateLimiter::increment(implode('|', [$user->email, '127.0.0.1']), amount: 10);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(422);

    $response->assertExactJson([
        'message' => 'Too many login attempts. Please try again in 60 seconds.',
        'errors' => [
            'email' => [
                'Too many login attempts. Please try again in 60 seconds.',
            ],
        ],
    ]);
});
