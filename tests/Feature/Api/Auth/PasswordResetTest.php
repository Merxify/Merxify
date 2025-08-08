<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

it('can request reset password link', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/api/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

it('can reset password with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/api/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
        $response = $this->post('/api/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(200);

        return true;
    });
});

it('cannot reset password with invalid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/api/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function (object $notification) use ($user) {
        $response = $this->post('/api/reset-password', [
            'token' => 'wrong_token',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        $response->assertSessionHasErrors(['email']);

        return true;
    });
});

it('cannot request reset password link with invalid email', function () {
    Notification::fake();

    $user = User::factory()->create();

    $response = $this->post('/api/forgot-password', ['email' => 'wrong@email.com']);

    Notification::assertNotSentTo($user, ResetPassword::class);

    $response->assertSessionHasErrors(['email']);
});
