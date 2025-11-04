<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

it('can send verification notification', function () {
    Notification::fake();

    $user = Sanctum::actingAs(
        User::factory()->unverified()->create(),
        ['*'],
    );

    $this->actingAs($user)
        ->postJson(route('verification.send'));

    Notification::assertSentTo($user, VerifyEmail::class);
});

it('does not send verification notification if email is verified', function () {
    Notification::fake();

    $user = Sanctum::actingAs(
        User::factory()->create(),
        ['*'],
    );

    $this->actingAs($user)
        ->postJson(route('verification.send'));

    Notification::assertNothingSent();
});
