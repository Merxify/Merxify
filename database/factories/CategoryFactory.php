<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name.'-'.$this->faker->unique()->numberBetween(1, 999)),
            'description' => $this->faker->text(),
            'meta_title' => $this->faker->unique()->word(),
            'meta_description' => $this->faker->text(),
            'meta_keywords' => $this->faker->unique()->words(3, true),
            'is_active' => $this->faker->boolean(),
            'parent_id' => null,
        ];
    }
}
