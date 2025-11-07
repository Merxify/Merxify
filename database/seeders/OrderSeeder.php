<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::customers()->get();

        $products = Product::active()->get();

        // Create 200 orders
        Order::factory()->count(200)->create([
            'user_id' => $customers->random()?->id,
        ])->each(function ($order) use ($products) {
            // Create 1-5 order items
            $itemCount = rand(1, 5);

            for ($i = 0; $i < $itemCount; $i++) {
                $product = $products->random();
                $variant = $product->variants()->active()->first();

                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'product_sku' => $variant?->sku ?? $product->sku,
                    'price' => $variant?->price ?? $product->price,
                ]);
            }
        });
    }
}
