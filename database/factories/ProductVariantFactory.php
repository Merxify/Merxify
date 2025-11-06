<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $basePrice = $this->faker->randomFloat(2, 10, 200);

        return [
            'product_id' => Product::factory(),
            'sku' => 'VAR-'.strtoupper($this->faker->bothify('??##??##')),
            'price' => $basePrice,
            'weight' => $this->faker->randomFloat(3, 0.1, 10),
            'attribute_values' => [
                'color' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Black', 'White']),
                'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            ],
            'is_active' => $this->faker->boolean(95),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
