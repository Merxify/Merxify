<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;

beforeEach(function () {
    $this->cart = Cart::factory()->create();
    $this->product = Product::factory()->create();
});

it('can create a cart item', function () {
    CartItem::create([
        'cart_id' => $this->cart->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 10,
    ]);

    $this->assertDatabaseCount('cart_items', 1);

    $this->assertDatabaseHas('cart_items', [
        'cart_id' => $this->cart->id,
    ]);
});

it('can update a cart item', function () {
    $cartItem = CartItem::create([
        'cart_id' => $this->cart->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 10,
    ]);

    $cart = Cart::factory()->create();
    $cartItem->update([
        'cart_id' => $cart->id,
    ]);

    $this->assertDatabaseCount('cart_items', 1);

    $this->assertDatabaseHas('cart_items', [
        'cart_id' => $cart->id,
    ]);
});

it('can delete a cart item', function () {
    $cartItem = CartItem::create([
        'cart_id' => $this->cart->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 10,
    ]);

    $cartItem->delete();

    $this->assertDatabaseCount('cart_items', 0);

    $this->assertDatabaseMissing('cart_items', [
        'cart_id' => $this->cart->id,
    ]);
});

it('can get cartItem-cart relationship', function () {
    $cartItem = CartItem::create([
        'cart_id' => $this->cart->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 10,
    ]);

    $this->assertEquals($cartItem->cart->id, $this->cart->id);
});

it('can get cartItem-product relationship', function () {
    $cartItem = CartItem::create([
        'cart_id' => $this->cart->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 10,
    ]);

    $this->assertEquals($cartItem->product->id, $this->product->id);
});
