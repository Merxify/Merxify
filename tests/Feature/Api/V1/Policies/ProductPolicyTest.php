<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

it('can perform full crud on products if admin', function () {
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/admin/products', [
        'name' => 'Product 1',
        'slug' => 'product-1',
        'sku' => 'SKU-CAM001',
        'type' => 'simple',
        'status' => 'active',
        'price' => 1070.14,
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/admin/products')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/products/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Product 1',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/products/1', [
        'name' => 'Product 2',
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Product 2',
                    'slug' => 'product-1',
                ],
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/admin/products/1')
        ->assertStatus(200);
});

it('can only view products if customer', function () {
    $customer = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'customer@merxify.app',
    ]);

    Sanctum::actingAs($customer, ['*']);

    Product::create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'sku' => 'SKU-CAM001',
        'type' => 'simple',
        'status' => 'active',
        'price' => 1070.14,
    ]);

    // Create
    $response = $this->postJson('/api/v1/admin/products', [
        'name' => 'Product 2',
        'slug' => 'product-2',
        'sku' => 'SKU-CAM002',
        'type' => 'simple',
        'status' => 'active',
        'price' => 1070.14,
    ]);

    $response->assertStatus(401);

    // Read
    $this->getJson('/api/v1/admin/products')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/products/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Product 1',
                    'slug' => 'product-1',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/products/1', [
        'name' => 'Product 2',
    ])->assertStatus(401);

    // Delete
    $this->deleteJson('/api/v1/admin/products/1')
        ->assertStatus(401);
});
