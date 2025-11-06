<?php

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
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 100)
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
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
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
            'name' => 'Product 1',
            'slug' => 'product-1',
            'sku' => 'SKU-CAM001',
            'type' => 'simple',
            'status' => 'active',
            'price' => 1070.14,
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
            ->where('data.type', 'product')
            ->where('data.id', 1)
            ->where('data.attributes.slug', 'product-1')
        )
        ->assertStatus(201);
});

it('can update a product', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $product = Product::create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'sku' => 'SKU-CAM001',
        'type' => 'simple',
        'status' => 'active',
        'price' => 1070.14,
    ]);

    $response = $this
        ->actingAs($user)
        ->patchJson("/api/v1/admin/products/$product->id", [
            'name' => 'Product 2',
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
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

    $product = Product::create([
        'name' => 'Product 1',
        'slug' => 'product-1',
        'sku' => 'SKU-CAM001',
        'type' => 'simple',
        'status' => 'active',
        'price' => 1070.14,
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
