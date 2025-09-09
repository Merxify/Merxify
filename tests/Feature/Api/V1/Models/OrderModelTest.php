<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can create an order', function () {
    Order::create([
        'user_id' => $this->user->id,
        'total_amount' => 100.50,
        'status' => 'pending',
        'shipping_address' => 'Some address',
        'billing_address' => 'Some other address',
    ]);

    $this->assertDatabaseCount('orders', 1);

    $this->assertDatabaseHas('orders', [
        'user_id' => $this->user->id,
        'total_amount' => 100.50,
    ]);
});

it('can update an order', function () {
    $order = Order::create([
        'user_id' => $this->user->id,
        'total_amount' => 100.50,
        'status' => 'pending',
        'shipping_address' => 'Some address',
        'billing_address' => 'Some other address',
    ]);

    $order->update([
        'total_amount' => 30.12,
    ]);

    $this->assertDatabaseCount('orders', 1);

    $this->assertDatabaseHas('orders', [
        'total_amount' => 30.12,
    ]);
});

it('can delete an order', function () {
    $order = Order::create([
        'user_id' => $this->user->id,
        'total_amount' => 100.50,
        'status' => 'pending',
        'shipping_address' => 'Some address',
        'billing_address' => 'Some other address',
    ]);

    $order->delete();

    $this->assertDatabaseCount('orders', 0);

    $this->assertDatabaseMissing('orders', [
        'id' => $order->id,
    ]);
});

it('can get order-user relationship', function () {
    $order = Order::create([
        'user_id' => $this->user->id,
        'total_amount' => 100.50,
        'status' => 'pending',
        'shipping_address' => 'Some address',
        'billing_address' => 'Some other address',
    ]);

    $this->assertEquals($order->user->id, $this->user->id);
});

it('can get order-items relationship', function () {
    $order = Order::create([
        'user_id' => $this->user->id,
        'total_amount' => 100.50,
        'status' => 'pending',
        'shipping_address' => 'Some address',
        'billing_address' => 'Some other address',
    ]);

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
    ]);

    $this->assertEquals($order->items->first()->id, $orderItem->id);
});

it('can get order-payments relationship', function () {
    $order = Order::create([
        'user_id' => $this->user->id,
        'total_amount' => 100.50,
        'status' => 'pending',
        'shipping_address' => 'Some address',
        'billing_address' => 'Some other address',
    ]);

    $payment = Payment::factory()->create([
        'order_id' => $order->id,
    ]);

    $this->assertEquals($order->payments->first()->id, $payment->id);
});
