<?php

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

it('can get relationship with user model', function () {
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->assertEquals($user->id, $order->user->id);
});

it('can get relationship with order item model', function () {
    $user = User::factory()->create();

    $order = Order::factory()->create([
        'user_id' => $user->id,
    ]);
    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
    ]);

    $this->assertEquals($orderItem->id, $order->items()->first()->id);
});

it('can query pending order models', function () {
    Order::factory()->create([
        'status' => 'pending',
    ]);

    Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertEquals(1, Order::pending()->count());
});

it('can query processing order models', function () {
    Order::factory()->create([
        'status' => 'processing',
    ]);

    Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertEquals(1, Order::processing()->count());
});

it('can query shipped order models', function () {
    Order::factory()->create([
        'status' => 'shipped',
    ]);

    Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertEquals(1, Order::shipped()->count());
});

it('can query delivered order models', function () {
    Order::factory()->create([
        'status' => 'delivered',
    ]);

    Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertEquals(1, Order::delivered()->count());
});

it('can check if order is pending', function () {
    $pending = Order::factory()->create([
        'status' => 'pending',
    ]);

    $cancelled = Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertTrue($pending->isPending());
    $this->assertFalse($cancelled->isPending());
});

it('can check if order is processing', function () {
    $processing = Order::factory()->create([
        'status' => 'processing',
    ]);

    $cancelled = Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertTrue($processing->isProcessing());
    $this->assertFalse($cancelled->isProcessing());
});

it('can check if order is shipped', function () {
    $shipped = Order::factory()->create([
        'status' => 'shipped',
    ]);

    $cancelled = Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertTrue($shipped->isShipped());
    $this->assertFalse($cancelled->isShipped());
});

it('can check if order is delivered', function () {
    $delivered = Order::factory()->create([
        'status' => 'delivered',
    ]);

    $cancelled = Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertTrue($delivered->isDelivered());
    $this->assertFalse($cancelled->isDelivered());
});

it('can check if order is cancelled', function () {
    $pending = Order::factory()->create([
        'status' => 'pending',
    ]);

    $cancelled = Order::factory()->create([
        'status' => 'cancelled',
    ]);

    $this->assertFalse($pending->isCancelled());
    $this->assertTrue($cancelled->isCancelled());
});

it('can generate order number', function () {
    $address = Address::factory()->create();
    $user = User::factory()->create();
    $order = Order::create([
        'user_id' => $user->id,
        'status' => 'processing',
        'currency' => 'USD',
        'total_amount' => 1000,
        'billing_address' => [
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'company' => $address->company,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country_code' => $address->country_code,
        ],
        'shipping_address' => [
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'company' => $address->company,
            'address_line_1' => $address->address_line_1,
            'address_line_2' => $address->address_line_2,
            'city' => $address->city,
            'state' => $address->state,
            'postal_code' => $address->postal_code,
            'country_code' => $address->country_code,
        ],
        'email' => $user->email,
    ]);
    $orderNumber = $order->generateOrderNumber();
    $this->assertStringContainsString('ORD-', $orderNumber);
    $this->assertStringContainsString('ORD-', $order->order_number);
});
