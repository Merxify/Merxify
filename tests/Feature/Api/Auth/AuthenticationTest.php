<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can create authenticate', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(200);

});

it('cannot authenticate with wrong credentials', function () {
    $user = User::factory()->create();

    $response = $this->post('/api/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors(['email']);

    $response2 = $this->post('/api/login', [
        'email' => 'wrong@email.com',
        'password' => 'password',
    ]);

    $response2->assertSessionHasErrors(['email']);
});

it('can logout user', function () {
    $user = Sanctum::actingAs(
        User::factory()->create(),
        ['*']
    );

    $response = $this->post('/api/logout');

    $response->assertNoContent();

    $this->assertEmpty($user->tokens);
});
