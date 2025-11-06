<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $attributes = Attribute::all();

        // Create 100 products
        Product::factory()->count(100)->create([
            'status' => 'active',
        ])->each(function (Product $product) use ($categories, $attributes) {
            // Assign 1-3 random categories
            $productCategories = $categories->random(rand(1, 3));
            $product->categories()->attach($productCategories);

            // Assign random attributes
            $productAttributes = $attributes->random(rand(2, 5));
            $productAttributes->each(function (Attribute $attribute) use ($product) {
                $value = match ($attribute->type) {
                    'text' => fake()->word(),
                    'number' => fake()->numberBetween(1, 100),
                    'boolean' => fake()->boolean(),
                    'select' => fake()->randomElement($attribute->options ?: ['Option 1']),
                    'multiselect' => fake()->randomElement($attribute->options ?: ['Option 1'], rand(1, 2)),
                    'date' => fake()->date(),
                    'textarea' => fake()->paragraph(),
                    default => fake()->word(),
                };

                $product->attributes()->attach($attribute, ['value' => json_encode($value)]);
            });
        });
    }
}
