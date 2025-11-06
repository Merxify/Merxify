<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $products = [
            'iPhone 15 Pro', 'MacBook Air M3', 'AirPods Pro', 'Vintage Denim Jacket',
            'Running Sneakers', 'Wireless Headphones', 'Gaming Laptop', 'Smart Watch',
            'Organic Cotton T-Shirt', 'Leather Wallet', 'Bluetooth Speaker', 'Coffee Maker',
            'Yoga Mat', 'Backpack', 'Sunglasses', 'Wireless Mouse', 'Desk Lamp',
            'Water Bottle', 'Phone Case', 'Keyboard',
        ];

        $name = $this->faker->randomElement($products);
        $price = $this->faker->randomFloat(2, 9.99, 999.99);
        $comparePrice = $this->faker->boolean(30) ? $price * $this->faker->numberBetween(110, 150) / 100 : null;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(4),
            'short_description' => $this->faker->sentence(10),
            'sku' => 'SKU-'.strtoupper($this->faker->bothify('??##??##')),
            'type' => $this->faker->randomElement(['simple', 'configurable', 'bundle', 'digital']),
            'status' => $this->faker->randomElement(['draft', 'active', 'inactive', 'out_of_stock']),
            'price' => $price,
            'weight' => $this->faker->randomFloat(3, 0.1, 50),
            'dimensions' => [
                'length' => $this->faker->randomFloat(2, 1, 50),
                'width' => $this->faker->randomFloat(2, 1, 50),
                'height' => $this->faker->randomFloat(2, 1, 50),
            ],
            'meta_data' => [
                'meta_title' => $name.' - Shop Now',
                'meta_descriptions' => 'Buy '.$name.' at the best price with fast shipping.',
                'meta_keywords' => strtolower(str_replace(' ', ', ', $name)),
            ],
            'options' => $this->faker->boolean(20) ? [
                'warranty' => $this->faker->randomElement(['1 year', '2 years', '3 years']),
                'gift_wrap' => $this->faker->boolean(),
            ] : null,
        ];
    }

    public function simple(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'simple',
        ]);
    }

    public function configurable(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'configurable',
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}
