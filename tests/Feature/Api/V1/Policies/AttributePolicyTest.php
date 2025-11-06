<?php

use App\Models\Attribute;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can perform full crud on attributes if admin', function () {
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/admin/attributes', [
        'name' => 'Attribute 1',
        'slug' => 'attribute-1',
        'type' => 'text',
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/admin/attributes')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/attributes/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Attribute 1',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/attributes/1', [
        'name' => 'Attribute 2',
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Attribute 2',
                    'slug' => 'attribute-1',
                ],
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/admin/attributes/1')
        ->assertStatus(200);
});

it('can only view attributes if customer', function () {
    $customer = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'customer@merxify.app',
    ]);

    Sanctum::actingAs($customer, ['*']);

    Attribute::create([
        'name' => 'Attribute 1',
        'slug' => 'attribute-1',
        'type' => 'text',
    ]);

    // Create
    $response = $this->postJson('/api/v1/admin/attributes', [
        'name' => 'Attribute 2',
        'slug' => 'attribute-2',
        'type' => 'text',
    ]);

    $response->assertStatus(401);

    // Read
    $this->getJson('/api/v1/admin/attributes')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/attributes/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Attribute 1',
                    'slug' => 'attribute-1',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/attributes/1', [
        'name' => 'Attribute 2',
    ])->assertStatus(401);

    // Delete
    $this->deleteJson('/api/v1/admin/attributes/1')
        ->assertStatus(401);
});
