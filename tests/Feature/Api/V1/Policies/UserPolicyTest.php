<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can perform full crud on users if admin', function () {
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/admin/users', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/admin/users')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/users/2')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 2,
                'attributes' => [
                    'first_name' => 'John',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/users/2', [
        'first_name' => 'Jane',
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 2,
                'attributes' => [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                ],
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/admin/users/2')
        ->assertStatus(200);
});

it('can only view users if customer', function () {
    $customer = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'customer@merxify.app',
    ]);

    Sanctum::actingAs($customer, ['*']);

    User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
    ]);

    // Create
    $response = $this->postJson('/api/v1/admin/users', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john2@doe.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(401);

    // Read
    $this->getJson('/api/v1/admin/users')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/users/2')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 2,
                'attributes' => [
                    'first_name' => 'John',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/users/2', [
        'first_name' => 'Jane',
    ])->assertStatus(401);

    // Delete
    $this->deleteJson('/api/v1/admin/users/1')
        ->assertStatus(401);
});
