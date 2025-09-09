<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@merxify.test',
            'role' => 'admin',
        ]);

        // Customers
        User::factory(10)->create(['role' => 'customer']);

        // Categories + Products
        $categories = Category::factory(5)->create();
        $categories->each(function ($category) {
            Product::factory(10)->create(['category_id' => $category->id]);
        });

        // Orders
        $customers = User::where('role', 'customer')->get();
        foreach ($customers as $customer) {
            $order = Order::factory()->create([
                'user_id' => $customer->id,
                'status' => 'paid',
            ]);

            $products = Product::inRandomOrder()->take(3)->get();
            foreach ($products as $product) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                    'price' => $product->price,
                ]);
            }

            Payment::factory()->create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'status' => 'completed',
                'provider' => 'stripe',
            ]);
        }
    }
}
