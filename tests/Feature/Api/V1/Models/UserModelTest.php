<?php

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

beforeEach(function () {
    $this->category = Category::factory()->create();
});

it('can create a user', function () {
    User::create([
        'name' => 'Test User',
        'email' => 'test@merxify.test',
        'password' => Hash::make('password'),
    ]);

    $this->assertDatabaseCount('users', 1);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
    ]);
});

it('can update a user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@merxify.test',
        'password' => Hash::make('password'),
    ]);

    $user->update([
        'name' => 'John Doe',
    ]);

    $this->assertDatabaseCount('users', 1);

    $this->assertDatabaseHas('users', [
        'name' => 'John Doe',
    ]);
});

it('can delete a user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@merxify.test',
        'password' => Hash::make('password'),
    ]);

    $user->delete();

    $this->assertDatabaseCount('users', 0);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('can check if user is admin', function () {
    $user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@merxify.test',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);

    $this->assertTrue($user->isAdmin());

    $user = User::create([
        'name' => 'Customer User',
        'email' => 'customer@merxify.test',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

    $this->assertFalse($user->isAdmin());
});

it('can get user-order relationship', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@merxify.test',
        'password' => Hash::make('password'),
    ]);

    $order = Order::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->assertEquals($user->orders->first()->id, $order->id);
});

it('can get user-cart relationship', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@merxify.test',
        'password' => Hash::make('password'),
    ]);

    $cart = Cart::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->assertEquals($user->carts->first()->id, $cart->id);
});
