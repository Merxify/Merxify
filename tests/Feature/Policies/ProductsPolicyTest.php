<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('does not allow to view all products without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $response = $this->actingAs($user)->get('/api/v1/products');

    $response->assertStatus(403);
});

it('allows to view all products with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $response = $this->actingAs($user)->get('/api/v1/products');

    $response->assertStatus(200);
});

it('does not allow to view single product without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    Product::factory()->create();

    $response = $this->actingAs($user)->get('/api/v1/products/1');

    $response->assertStatus(403);
});

it('allows to view single product with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    Product::factory()->create();

    $response = $this->actingAs($user)->get('/api/v1/products/1');

    $response->assertStatus(200);
});

it('does not allow to store new product without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $category = Category::factory()->create();

    $response = $this->actingAs($user)->post('/api/v1/products', [
        'name' => 'newProduct',
        'price' => 1,
        'category_id' => $category->id,
    ]);

    $response->assertStatus(403);
});

it('allows to store new product with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $category = Category::factory()->create();
    $response = $this->actingAs($user)->postJson('/api/v1/products', [
        'name' => 'newProduct',
        'price' => 1,
        'category_id' => $category->id,
    ]);

    $response->assertStatus(201);
});

it('does not allow to update product without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $product = Product::factory()->create([
        'name' => 'newProduct',
        'price' => 1,
    ]);

    $response = $this->actingAs($user)->patch('/api/v1/products/'.$product->id, [
        'name' => 'anotherProduct',
    ]);

    $response->assertStatus(403);
});

it('allows to update product with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $product = Product::factory()->create([
        'name' => 'newProduct',
        'price' => 1,
    ]);

    $response = $this->actingAs($user)->patch('/api/v1/products/'.$product->id, [
        'name' => 'anotherProduct',
    ]);

    $response->assertStatus(200);
});

it('does not allow to delete product without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $product = Product::factory()->create([
        'name' => 'newProduct',
        'price' => 1,
    ]);

    $response = $this->actingAs($user)->delete('/api/v1/products/'.$product->id);

    $response->assertStatus(403);
});

it('allows to delete product with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $product = Product::factory()->create([
        'name' => 'newProduct',
        'price' => 1,
    ]);

    $response = $this->actingAs($user)->delete('/api/v1/products/'.$product->id);

    $response->assertStatus(200);
});
