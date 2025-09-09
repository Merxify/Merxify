<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can create a cart', function () {
    Cart::create([
        'user_id' => $this->user->id,
    ]);

    $this->assertDatabaseCount('carts', 1);

    $this->assertDatabaseHas('carts', [
        'user_id' => $this->user->id,
    ]);
});

it('can update a cart', function () {
    $cart = Cart::create([
        'user_id' => $this->user->id,
    ]);

    $user = User::factory()->create();
    $cart->update([
        'user_id' => $user->id,
    ]);

    $this->assertDatabaseCount('carts', 1);

    $this->assertDatabaseHas('carts', [
        'user_id' => $user->id,
    ]);
});

it('can delete a cart', function () {
    $cart = Cart::create([
        'user_id' => $this->user->id,
    ]);

    $cart->delete();

    $this->assertDatabaseCount('carts', 0);

    $this->assertDatabaseMissing('carts', [
        'user_id' => $this->user->id,
    ]);
});

it('can get cart-user relationship', function () {
    $cart = Cart::create([
        'user_id' => $this->user->id,
    ]);

    $this->assertEquals($cart->user->id, $this->user->id);
});

it('can get cart-items relationship', function () {
    $cart = Cart::create([
        'user_id' => $this->user->id,
    ]);

    $cartItem = CartItem::factory()->create([
        'cart_id' => $cart->id,
    ]);

    $this->assertEquals($cart->items->first()->id, $cartItem->id);
});
