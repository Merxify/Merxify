<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CartItem>
 */
class CartItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 5, 200);

        return [
            'cart_id' => Cart::factory(),
            'product_id' => Product::factory(),
            'variant_id' => $this->faker->boolean(40) ? ProductVariant::factory() : null,
            'quantity' => $this->faker->numberBetween(1, 5),
            'price' => $price,
            'product_options' => $this->faker->boolean(20) ? [
                'gift_wrap' => $this->faker->boolean(),
                'custom_message' => $this->faker->sentence(),
            ] : null,
        ];
    }

    public function withVariant(): static
    {
        return $this->state(fn (array $attributes) => [
            'variant_id' => ProductVariant::factory(),
        ]);
    }
}
