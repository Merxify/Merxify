<?php

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can perform full crud on categories if admin', function () {
    $admin = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);

    Sanctum::actingAs($admin, ['*']);

    // Create
    $response = $this->postJson('/api/v1/admin/categories', [
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
    ]);

    $response->assertStatus(201);

    // Read
    $this->getJson('/api/v1/admin/categories')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/categories/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Category 1',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/categories/1', [
        'name' => 'Category 2',
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Category 2',
                    'slug' => 'category-1',
                ],
            ],
        ]);

    // Delete
    $this->deleteJson('/api/v1/admin/categories/1')
        ->assertStatus(200);
});

it('can only view categories if customer', function () {
    $customer = User::factory()->create([
        'first_name' => 'Customer',
        'last_name' => 'User',
        'email' => 'customer@merxify.app',
    ]);

    Sanctum::actingAs($customer, ['*']);

    Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
    ]);

    // Create
    $response = $this->postJson('/api/v1/admin/categories', [
        'name' => 'Category 2',
        'slug' => 'category-2',
        'description' => 'Category 2 description',
        'meta_title' => 'Category 2 Meta Title',
        'meta_description' => 'Category 2 Meta Description',
    ]);

    $response->assertStatus(401);

    // Read
    $this->getJson('/api/v1/admin/categories')
        ->assertStatus(200);

    $this->getJson('/api/v1/admin/categories/1')
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => 1,
                'attributes' => [
                    'name' => 'Category 1',
                    'slug' => 'category-1',
                ],
            ],
        ]);

    // Update
    $this->putJson('/api/v1/admin/categories/1', [
        'name' => 'Category 2',
    ])->assertStatus(401);

    // Delete
    $this->deleteJson('/api/v1/admin/categories/1')
        ->assertStatus(401);
});
