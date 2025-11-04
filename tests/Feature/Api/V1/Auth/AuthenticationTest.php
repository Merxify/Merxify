<?php

use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\Sanctum;

it('can authenticate users', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/login', [
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

it('can not authenticate users with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/v1/auth/login', [
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

it('can logout users', function () {
    $user = Sanctum::actingAs(
        User::factory()->create(),
        ['*'],
    );

    $response = $this->postJson('/api/v1/auth/logout');

    $response->assertOk();

    $this->assertEmpty($user->tokens);
});

it('can rate limit user logins', function () {
    $user = User::factory()->create();

    RateLimiter::increment(implode('|', [$user->email, '127.0.0.1']), amount: 10);

    $response = $this->postJson('/api/v1/auth/login', [
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
