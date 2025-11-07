<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;

it('can get relationship with order model', function () {
    $order = Order::factory()->create();

    $orderItem = OrderItem::factory()->create([
        'order_id' => $order->id,
    ]);

    $this->assertEquals($order->id, $orderItem->order->id);
});

it('can get relationship with product model', function () {
    $product = Product::factory()->create();

    $orderItem = OrderItem::factory()->create([
        'product_id' => $product->id,
    ]);

    $this->assertEquals($product->id, $orderItem->product->id);
});

it('can get relationship with product variant model', function () {
    $product = Product::factory()->create();
    $variant = ProductVariant::factory()->create([
        'product_id' => $product->id,
    ]);

    $orderItem = OrderItem::factory()->create([
        'product_id' => $product->id,
        'variant_id' => $variant->id,
    ]);

    $this->assertEquals($variant->id, $orderItem->variant->id);
});
