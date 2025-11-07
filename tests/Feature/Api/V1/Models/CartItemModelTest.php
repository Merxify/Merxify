<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->cart = Cart::factory()->create(['user_id' => $this->user->id]);
});

it('can get cart relationship', function () {
    $cartItem = CartItem::factory()->create(['cart_id' => $this->cart->id]);

    $this->assertEquals($this->cart->id, $cartItem->cart->id);
});

it('can get product relationship', function () {
    $product = Product::factory()->create();
    $cartItem = CartItem::factory()->create([
        'cart_id' => $this->cart->id,
        'product_id' => $product->id,
    ]);

    $this->assertEquals($product->id, $cartItem->product->id);
});

it('can get product variant relationship', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
    $cartItem = CartItem::factory()->create([
        'cart_id' => $this->cart->id,
        'product_id' => $product->id,
        'variant_id' => $variant->id,
    ]);

    $this->assertEquals($variant->id, $cartItem->variant->id);
});

it('can get total price of items', function () {
    $cartItem = CartItem::factory()->create([
        'cart_id' => $this->cart->id,
        'price' => 5,
        'quantity' => 2,
    ]);

    $this->assertEquals((5 * 2), $cartItem->total);
});
