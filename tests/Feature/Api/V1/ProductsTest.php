<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );
});

it('shows all products', function () {
    Product::factory(10)->create();

    $response = $this->actingAs($this->user)->get('/api/v1/products');

    $response
        ->assertStatus(200)
        ->assertJsonCount(10, 'data');
});

it('can paginate products based on user query', function () {
    Product::factory(10)->create();

    $response = $this->actingAs($this->user)->get('/api/v1/products?per_page=2');
    $response
        ->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJson([
            'links' => [],
        ]);
});

it('can show single product', function () {
    $product = Product::factory()->create();

    $response = $this->actingAs($this->user)->get('/api/v1/products/'.$product->id);

    $response
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
            ],
        ]);
});

it('can delete product', function () {
    $product = Product::factory()->create([
        'name' => 'newProduct',
        'price' => 1,
    ]);

    $response = $this->actingAs($this->user)->delete('/api/v1/products/'.$product->id);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

it('can create product', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->post('/api/v1/products', [
        'name' => 'newProduct',
        'price' => 1,
        'category_id' => $category->id,
    ]);

    $response
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                'name' => 'newProduct',
            ],
        ]);
});

it('can update product', function () {
    $product = Product::factory()->create([
        'name' => 'newProduct',
        'price' => 1,
    ]);

    $response = $this->actingAs($this->user)->patch('/api/v1/products/'.$product->id, [
        'name' => 'anotherProduct',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'anotherProduct',
            ],
        ]);
});
