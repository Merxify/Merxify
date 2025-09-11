<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->seed('Database\\Seeders\\RolesAndPermissionsSeeder');
    $this->seed('Database\\Seeders\\UserSeeder');
});

test('admin can perform full crud on products', function () {
    $admin = User::where('email', 'admin@merxify.test')->first();

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/products', [
        'name' => 'Test Product',
        'slug' => 'test-product',
        'category_id' => Category::factory()->create()->id,
        'description' => 'Test Product Description',
        'price' => 10,
        'sku' => 'test-product',
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/products')
        ->assertStatus(200);

    $this->getJson('/api/v1/products/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Test Product',
                'price' => 10,
            ],
        ]);

    // Update
    $this->putJson('/api/v1/products/1', [
        'price' => 100,
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Test Product',
                'price' => 100,
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/products/1')
        ->assertStatus(200);
});

test('staff can view, create and update but not delete products', function () {
    $staff = User::where('email', 'staff@merxify.test')->first();

    Sanctum::actingAs($staff, ['*']);

    // Create
    $response = $this->postJson('/api/v1/products', [
        'name' => 'Test Product',
        'slug' => 'test-product',
        'category_id' => Category::factory()->create()->id,
        'description' => 'Test Product Description',
        'price' => 10,
        'sku' => 'test-product',
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/products')
        ->assertStatus(200);

    $this->getJson('/api/v1/products/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Test Product',
                'price' => 10,
            ],
        ]);

    // Update
    $this->putJson('/api/v1/products/1', [
        'price' => 100,
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Test Product',
                'price' => 100,
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/products/1')
        ->assertStatus(401);
});

test('customer can only view products', function () {
    $staff = User::where('email', 'customer@merxify.test')->first();

    Sanctum::actingAs($staff, ['*']);

    Product::create([
        'name' => 'Test Product',
        'slug' => 'test-product',
        'category_id' => Category::factory()->create()->id,
        'description' => 'Test Product Description',
        'price' => 10,
        'sku' => 'test-product',
    ]);

    // Create
    $response = $this->postJson('/api/v1/products', [
        'name' => 'Test Product 2',
        'slug' => 'test-product-2',
        'category_id' => Category::factory()->create()->id,
        'description' => 'Test Product Description 2',
        'price' => 20,
        'sku' => 'test-product-2',
    ]);

    $response->assertStatus(401);

    // Read
    $this->getJson('/api/v1/products')
        ->assertStatus(200);

    $this->getJson('/api/v1/products/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Test Product',
                'price' => 10,
            ],
        ]);

    // Update
    $this->putJson('/api/v1/products/1', [
        'price' => 100,
    ])->assertStatus(401);

    // Delete
    $this->deleteJson('/api/v1/products/1')
        ->assertStatus(401);
});
