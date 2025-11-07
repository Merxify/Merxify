<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->cart = Cart::factory()->create(['user_id' => $this->user->id]);
});

it('can get user relationship', function () {
    $this->assertEquals($this->user->id, $this->cart->user->id);
});

it('can get cart items relationship', function () {
    CartItem::factory()->count(3)->create(['cart_id' => $this->cart->id]);

    $this->assertEquals(3, $this->cart->items()->count());
});

it('can get total price of items', function () {
    CartItem::factory()->create([
        'cart_id' => $this->cart->id,
        'price' => 5,
        'quantity' => 2,
    ]);

    CartItem::factory()->create([
        'cart_id' => $this->cart->id,
        'price' => 2,
        'quantity' => 3,
    ]);

    $this->assertEquals((5 * 2) + (2 * 3), $this->cart->total);
});

it('can get item count of items', function () {
    CartItem::factory()->create([
        'cart_id' => $this->cart->id,
        'quantity' => 2,
    ]);

    CartItem::factory()->create([
        'cart_id' => $this->cart->id,
        'quantity' => 3,
    ]);

    $this->assertEquals(2 + 3, $this->cart->item_count);
});

it('can add item to cart', function () {
    $product = Product::factory()->create();

    $this->cart->addItem($product);

    $this->assertEquals(1, $this->cart->items()->count());
});

it('can increase quantity when item already in cart', function () {
    $product = Product::factory()->create();

    $this->cart->addItem($product);

    $this->cart->addItem($product, 3);

    $this->assertEquals(1, $this->cart->items()->count());

    $this->assertEquals(4, $this->cart->item_count);
});

it('can remove item from cart', function () {
    $product = Product::factory()->create();

    $this->cart->addItem($product);

    $this->cart->removeItem($product);

    $this->assertEquals(0, $this->cart->items()->count());
});

it('can clear cart', function () {
    $products = Product::factory(10)->create();

    foreach ($products as $product) {
        $this->cart->addItem($product);
    }

    $this->assertEquals(10, $this->cart->items()->count());

    $this->cart->clear();

    $this->assertEquals(0, $this->cart->items()->count());
});
