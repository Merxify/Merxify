<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::customers()->get();

        $products = Product::active()->get();

        Cart::factory()->count(20)->create([
            'user_id' => $customers->random()?->id,
        ])->each(function ($cart) use ($products) {
            // Add 1-3 items to cart
            $itemCount = rand(1, 3);

            for ($i = 0; $i < $itemCount; $i++) {
                $product = $products->random();
                $variant = $product->variants()->active()->first();

                CartItem::factory()->create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'price' => $variant->price ?? $product->price,
                ]);
            }
        });
    }
}
