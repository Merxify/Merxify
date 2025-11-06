<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Attribute>
 */
class AttributeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attributes = [
            'Color' => ['text', false, ['Red', 'Blue', 'Green', 'Black', 'White']],
            'Size' => ['select', true, ['XS', 'S', 'M', 'L', 'XL', 'XXL']],
            'Material' => ['select', false, ['Cotton', 'Polyester', 'Wool', 'Silk', 'Leather']],
            'Brand' => ['text', false, []],
            'Weight' => ['number', false, []],
            'Dimensions' => ['text', false, []],
            'Memory' => ['select', true, ['4GB', '8GB', '16GB', '32GB', '64GB']],
            'Storage' => ['select', true, ['128GB', '256GB', '512GB', '1TB']],
        ];

        $attributeName = $this->faker->randomElement(array_keys($attributes));
        [$type, $isVariant, $options] = $attributes[$attributeName];

        return [
            'name' => $attributeName,
            'slug' => Str::slug($attributeName),
            'type' => $type,
            'is_required' => $this->faker->boolean(30),
            'is_filterable' => $this->faker->boolean(70),
            'is_variant' => $isVariant,
            'options' => $options ?: null,
        ];
    }

    public function variant(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_variant' => true,
        ]);
    }

    public function filterable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_filterable' => true,
        ]);
    }
}
