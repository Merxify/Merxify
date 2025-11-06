<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            'Color' => ['select', true, ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Purple']],
            'Size' => ['select', true, ['XS', 'S', 'M', 'L', 'XL', 'XXL']],
            'Material' => ['select', false, ['Cotton', 'Polyester', 'Wool', 'Silk', 'Leather', 'Denim']],
            'Brand' => ['text', false, []],
            'Memory' => ['select', true, ['4GB', '8GB', '16GB', '32GB', '64GB', '128GB']],
            'Storage' => ['select', true, ['128GB', '256GB', '512GB', '1TB', '2TB']],
            'Screen Size' => ['select', false, ['13"', '14"', '15"', '16"', '17"']],
            'Weight' => ['number', false, []],
        ];

        foreach ($attributes as $name => [$type, $isVariant, $options]) {
            Attribute::factory()->create([
                'name' => $name,
                'slug' => Str::slug($name),
                'type' => $type,
                'is_variant' => $isVariant,
                'options' => $options ?: null,
                'is_filterable' => true,
            ]);
        }
    }
}
