<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->randomFloat(2, 5, 200);
        $total = $quantity * $price;

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'variant_id' => $this->faker->boolean(40) ? ProductVariant::factory() : null,
            'product_name' => $this->faker->words(3, true),
            'product_sku' => 'SKU-'.strtoupper($this->faker->bothify('??##??##')),
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total,
            'product_options' => $this->faker->boolean(20) ? [
                'gift_wrap' => $this->faker->boolean(),
                'warranty' => $this->faker->randomElement(['1 year', '2 years']),
            ] : null,
        ];
    }
}
