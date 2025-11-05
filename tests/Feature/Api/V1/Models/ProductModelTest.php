<?php

use App\Models\Category;
use App\Models\Product;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

it('can create a new product', function () {
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
        'category_id' => $this->category->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
    ]);

    $this->assertDatabaseCount('products', 1);

    $this->assertDatabaseHas('products', [
        'id' => $product->id,
    ]);
});

it('can update a product', function () {
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
        'category_id' => $this->category->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
    ]);

    $product->update([
        'name' => 'Product 2',
    ]);

    $this->assertDatabaseCount('products', 1);

    $this->assertDatabaseHas('products', [
        'name' => 'Product 2',
    ]);
});

it('can delete a product', function () {
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
        'category_id' => $this->category->id,
        'meta_title' => 'Cameras Product 1 | Cameras',
        'meta_description' => 'Buy Cameras Product 1 from our Cameras range. Best prices and top quality guaranteed.',
        'meta_keywords' => 'cameras, cameras product 1, online store',
    ]);

    $product->delete();

    $this->assertDatabaseCount('products', 0);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});
