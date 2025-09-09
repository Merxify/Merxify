<?php

use App\Models\CartItem;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

it('can create a product', function () {
    Product::create([
        'category_id' => $this->category->id,
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock_qty' => 10,
        'sku' => 'SKU-1234',
        'is_active' => true,
    ]);

    $this->assertDatabaseCount('products', 1);

    $this->assertDatabaseHas('products', [
        'category_id' => $this->category->id,
        'name' => 'Product 1',
    ]);
});

it('can update a product', function () {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock_qty' => 10,
        'sku' => 'SKU-1234',
        'is_active' => true,
    ]);

    $product->update([
        'name' => 'Product 2',
    ]);

    $this->assertDatabaseCount('products', 1);

    $this->assertDatabaseHas('products', [
        'category_id' => $this->category->id,
        'name' => 'Product 2',
    ]);
});

it('can delete a product', function () {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock_qty' => 10,
        'sku' => 'SKU-1234',
        'is_active' => true,
    ]);

    $product->delete();

    $this->assertDatabaseCount('products', 0);

    $this->assertDatabaseMissing('products', [
        'id' => $product->id,
    ]);
});

it('can get product-category relationship', function () {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock_qty' => 10,
        'sku' => 'SKU-1234',
        'is_active' => true,
    ]);

    $this->assertEquals($product->category->id, $this->category->id);
});

it('can get product-cartItem relationship', function () {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock_qty' => 10,
        'sku' => 'SKU-1234',
        'is_active' => true,
    ]);

    $cartItem = CartItem::factory()->create([
        'product_id' => $product->id,
    ]);

    $this->assertEquals($product->cartItems->first()->id, $cartItem->id);
});

it('can get product-orderItem relationship', function () {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Product 1',
        'slug' => 'product-1',
        'description' => 'Product 1 description',
        'price' => 100,
        'stock_qty' => 10,
        'sku' => 'SKU-1234',
        'is_active' => true,
    ]);

    $orderItem = OrderItem::factory()->create([
        'product_id' => $product->id,
    ]);

    $this->assertEquals($product->orderItems->first()->id, $orderItem->id);
});
