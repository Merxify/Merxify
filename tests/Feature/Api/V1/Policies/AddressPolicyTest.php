<?php

use App\Models\Address;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can perform full crud on users if admin', function () {
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/admin/addresses', [
        'user_id' => $this->user->id,
        'type' => 'both',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_line_1' => 'Some random street address',
        'city' => 'Berlin',
        'state' => 'Berlin State',
        'postal_code' => '12345',
        'country_code' => 'DE',
        'is_default' => true,
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/admin/addresses')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/addresses/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'first_name' => 'John',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/addresses/1', [
        'first_name' => 'Jane',
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                ],
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/admin/addresses/1')
        ->assertStatus(200);
});

it('can only view users if customer', function () {
    $customer = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'customer@merxify.app',
    ]);

    Sanctum::actingAs($customer, ['*']);

    Address::create([
        'user_id' => $this->user->id,
        'type' => 'both',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_line_1' => 'Some random street address',
        'city' => 'Berlin',
        'state' => 'Berlin State',
        'postal_code' => '12345',
        'country_code' => 'DE',
        'is_default' => true,
    ]);

    // Create
    $response = $this->postJson('/api/v1/admin/addresses', [
        'user_id' => $this->user->id,
        'type' => 'both',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'address_line_1' => 'Some random street address',
        'city' => 'Berlin',
        'state' => 'Berlin State',
        'postal_code' => '12345',
        'country_code' => 'DE',
        'is_default' => true,
    ]);

    $response->assertStatus(401);

    // Read
    $this->getJson('/api/v1/admin/addresses')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/addresses/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'first_name' => 'John',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/addresses/1', [
        'first_name' => 'Jane',
    ])->assertStatus(401);

    // Delete
    $this->deleteJson('/api/v1/admin/addresses/1')
        ->assertStatus(401);
});
