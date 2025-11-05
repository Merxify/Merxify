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
        'name' => 'Site Admin',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/admin/products', [
        'name' => 'Cameras Product 1',
        'description' => 'This is a sample description for Cameras Product 1, part of the Cameras collection.',
        'short_description' => 'High-quality cameras item.',
        'slug' => 'cameras-product-1',
        'sku' => 'CAM001-FE0D5',
        'price' => 1070.14,
        'weight' => 4.69,
        'quantity' => 72,
        'dimensions' => [
            'length' => 40.5,
            'width' => 39.0,
            'height' => 10.8,
            'unit' => 'cm',
        ],
        'category_id' => $this->category->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
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
                    'name' => 'Cameras Product 1',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/products/1', [
        'name' => 'Cameras Product 2',
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Cameras Product 2',
                    'slug' => 'cameras-product-1',
                ],
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/admin/products/1')
        ->assertStatus(200);
});

it('can only view products if customer', function () {
    $customer = User::factory()->create([
        'name' => 'Site Customer',
        'email' => 'customer@merxify.app',
    ]);

    Sanctum::actingAs($customer, ['*']);

    Product::create([
        'name' => 'Cameras Product 1',
        'description' => 'This is a sample description for Cameras Product 1, part of the Cameras collection.',
        'short_description' => 'High-quality cameras item.',
        'slug' => 'cameras-product-1',
        'sku' => 'CAM001-FE0D5',
        'price' => 1070.14,
        'weight' => 4.69,
        'quantity' => 72,
        'dimensions' => [
            'length' => 40.5,
            'width' => 39.0,
            'height' => 10.8,
            'unit' => 'cm',
        ],
        'category_id' => $this->category->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
    ]);

    // Create
    $response = $this->postJson('/api/v1/admin/products', [
        'name' => 'Cameras Product 1',
        'description' => 'This is a sample description for Cameras Product 1, part of the Cameras collection.',
        'short_description' => 'High-quality cameras item.',
        'slug' => 'cameras-product-1',
        'sku' => 'CAM001-FE0D5',
        'price' => 1070.14,
        'weight' => 4.69,
        'quantity' => 72,
        'dimensions' => [
            'length' => 40.5,
            'width' => 39.0,
            'height' => 10.8,
            'unit' => 'cm',
        ],
        'category_id' => Category::factory()->create()->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
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
                    'name' => 'Cameras Product 1',
                    'slug' => 'cameras-product-1',
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
