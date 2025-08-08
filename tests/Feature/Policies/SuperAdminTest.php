<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can bypass permissions if Super-Admin', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'super-admin@example.com')->first(),
        ['*'],
    );

    $response = $this->actingAs($user)->get('/api/v1/products');

    $response->assertStatus(200);
});
