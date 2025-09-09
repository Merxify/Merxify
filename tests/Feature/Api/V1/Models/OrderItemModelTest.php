<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

beforeEach(function () {
    $this->order = Order::factory()->create();
    $this->product = Product::factory()->create();
});

it('can create an order item', function () {
    OrderItem::create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 23.45,
    ]);

    $this->assertDatabaseCount('order_items', 1);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $this->order->id,
        'price' => 23.45,
    ]);
});

it('can update an order item', function () {
    $orderItem = OrderItem::create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 23.45,
    ]);

    $orderItem->update([
        'price' => 30.12,
    ]);

    $this->assertDatabaseCount('order_items', 1);

    $this->assertDatabaseHas('order_items', [
        'price' => 30.12,
    ]);
});

it('can delete an order item', function () {
    $orderItem = OrderItem::create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 23.45,
    ]);

    $orderItem->delete();

    $this->assertDatabaseCount('order_items', 0);

    $this->assertDatabaseMissing('order_items', [
        'id' => $orderItem->id,
    ]);
});

it('can get orderItem-order relationship', function () {
    $orderItem = OrderItem::create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 23.45,
    ]);

    $this->assertEquals($orderItem->order->id, $this->order->id);
});

it('can get orderItem-product relationship', function () {
    $orderItem = OrderItem::create([
        'order_id' => $this->order->id,
        'product_id' => $this->product->id,
        'quantity' => 1,
        'price' => 23.45,
    ]);

    $this->assertEquals($orderItem->product->id, $this->product->id);
});
