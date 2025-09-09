<?php

use App\Models\Order;
use App\Models\Payment;

beforeEach(function () {
    $this->order = Order::factory()->create();
});

it('can create a payment', function () {
    Payment::create([
        'order_id' => $this->order->id,
        'amount' => 123.45,
        'status' => 'completed',
        'provider' => 'stripe',
        'transaction_id' => 'TXN-1234567',
    ]);

    $this->assertDatabaseCount('payments', 1);

    $this->assertDatabaseHas('payments', [
        'order_id' => $this->order->id,
        'amount' => 123.45,
    ]);
});

it('can update a payment', function () {
    $payment = Payment::create([
        'order_id' => $this->order->id,
        'amount' => 123.45,
        'status' => 'completed',
        'provider' => 'stripe',
        'transaction_id' => 'TXN-1234567',
    ]);

    $payment->update([
        'status' => 'refunded',
    ]);

    $this->assertDatabaseCount('payments', 1);

    $this->assertDatabaseHas('payments', [
        'status' => 'refunded',
    ]);
});

it('can delete a payment', function () {
    $payment = Payment::create([
        'order_id' => $this->order->id,
        'amount' => 123.45,
        'status' => 'completed',
        'provider' => 'stripe',
        'transaction_id' => 'TXN-1234567',
    ]);

    $payment->delete();

    $this->assertDatabaseCount('payments', 0);

    $this->assertDatabaseMissing('payments', [
        'id' => $payment->id,
    ]);
});

it('can get payment-order relationship', function () {
    $payment = Payment::create([
        'order_id' => $this->order->id,
        'amount' => 123.45,
        'status' => 'completed',
        'provider' => 'stripe',
        'transaction_id' => 'TXN-1234567',
    ]);

    $this->assertEquals($payment->order->id, $this->order->id);
});
