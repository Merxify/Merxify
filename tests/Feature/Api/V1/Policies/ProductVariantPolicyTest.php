<?php

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->product = Product::factory()->create();
});

it('can perform full crud on product variants if admin', function () {
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/admin/product-variants', [
        'product_id' => $this->product->id,
        'sku' => 'SKU-12345',
        'price' => 123.22,
        'attribute_values' => [
            'color' => ['red', 'blue'],
        ],
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/admin/product-variants')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/product-variants/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'sku' => 'SKU-12345',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/product-variants/1', [
        'sku' => 'SKU-12233',
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'sku' => 'SKU-12233',
                    'product_id' => $this->product->id,
                ],
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/admin/product-variants/1')
        ->assertStatus(200);
});

it('can only view product variants if customer', function () {
    $customer = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'customer@merxify.app',
    ]);

    Sanctum::actingAs($customer, ['*']);

    ProductVariant::create([
        'product_id' => $this->product->id,
        'sku' => 'SKU-12345',
        'price' => 123.22,
        'attribute_values' => [
            'color' => ['red', 'blue'],
        ],
    ]);

    // Create
    $response = $this->postJson('/api/v1/admin/product-variants', [
        'product_id' => $this->product->id,
        'sku' => 'SKU-12346',
        'price' => 123.22,
        'attribute_values' => [
            'color' => ['red', 'blue'],
        ],
    ]);

    $response->assertStatus(401);

    // Read
    $this->getJson('/api/v1/admin/product-variants')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/product-variants/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'sku' => 'SKU-12345',
                    'product_id' => $this->product->id,
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/product-variants/1', [
        'name' => 'Attribute 2',
    ])->assertStatus(401);

    // Delete
    $this->deleteJson('/api/v1/admin/product-variants/1')
        ->assertStatus(401);
});
