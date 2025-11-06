<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin2@merxify.app',
        'group' => 'admin',
    ]);
});

it('can show all products', function () {
    $this->seed(DatabaseSeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );
    $response = $this
        ->actingAs($user)
        ->getJson('/api/v1/admin/products');

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 240)
        )
        ->assertStatus(200);
});

it('can show single product', function () {
    $this->seed(DatabaseSeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $product = Product::first();

    $response = $this
        ->actingAs($user)
        ->getJson("/api/v1/admin/products/$product->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5)
            ->where('data.type', 'product')
            ->where('data.id', $product->id)
            ->where('data.attributes.slug', $product->slug)
        )
        ->assertStatus(200);
});

it('can create new product', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $response = $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/products', [
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

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5)
            ->where('data.type', 'product')
            ->where('data.id', 1)
            ->where('data.attributes.slug', 'cameras-product-1')
        )
        ->assertStatus(201);
});

it('can update a product', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
    ]);

    $product = Product::create([
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
        'category_id' => $category->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
    ]);

    $response = $this
        ->actingAs($user)
        ->patchJson("/api/v1/admin/products/$product->id", [
            'name' => 'Product 2',
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5)
            ->where('data.type', 'product')
            ->where('data.id', $product->id)
            ->where('data.attributes.name', 'Product 2')
        )
        ->assertStatus(200);
});

it('can delete a product', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
    ]);

    $product = Product::create([
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
        'category_id' => $category->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
    ]);

    $response = $this
        ->actingAs($user)
        ->deleteJson("/api/v1/admin/products/$product->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->hasAll('data', 'message', 'status')
            ->where('message', 'Product deleted successfully.')
        )
        ->assertStatus(200);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
