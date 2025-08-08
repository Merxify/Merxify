<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\Sanctum;

it('can verify email', function () {
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
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');
});

it('cannot verify email with invalid hash', function () {
    $user = Sanctum::actingAs(
        User::factory()->unverified()->create(),
        ['*'],
    );

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

it('cannot verify email if already verified', function () {
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

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertNotDispatched(Verified::class);
    $response->assertRedirect(config('app.frontend_url').'/dashboard?verified=1');
});

it('can send email verification link', function () {
    $user = Sanctum::actingAs(
        User::factory()->unverified()->create(),
        ['*'],
    );

    $response = $this->actingAs($user)->post(route('verification.send'));

    $response->assertJson(['status' => 'verification-link-sent']);
});

it('cannot send email verification link if already verified', function () {
    $user = Sanctum::actingAs(
        User::factory()->create(),
        ['*'],
    );

    $response = $this->actingAs($user)->post(route('verification.send'));

    $response->assertRedirect('/dashboard');
});
