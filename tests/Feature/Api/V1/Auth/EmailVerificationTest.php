<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;

test('email can be verified', function () {
    $user = Sanctum::actingAs(
        User::factory()->unverified()->create(),
        ['*'],
    );

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    $this->assertTrue($user->fresh()->hasVerifiedEmail());
    $response->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');
});

test('email is not verified with invalid hash', function () {
    $user = Sanctum::actingAs(
        User::factory()->unverified()->create(),
        ['*'],
    );

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    Event::assertNotDispatched(Verified::class);

    $this->assertFalse($user->fresh()->hasVerifiedEmail());
});

test('email is not verified with invalid user id', function () {
    $user = Sanctum::actingAs(
        User::factory()->unverified()->create(),
        ['*'],
    );

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => 123, 'hash' => sha1($user->email)]
    );

    $this->actingAs($user)->get($verificationUrl);

    Event::assertNotDispatched(Verified::class);

    $this->assertFalse($user->fresh()->hasVerifiedEmail());
});

test('already verified user visiting verification link is redirected without firing event again', function () {
    $user = Sanctum::actingAs(
        User::factory()->create(),
        ['*'],
    );

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $this->actingAs($user)->get($verificationUrl)
        ->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');

    Event::assertNotDispatched(Verified::class);

    $this->assertTrue($user->fresh()->hasVerifiedEmail());
});
