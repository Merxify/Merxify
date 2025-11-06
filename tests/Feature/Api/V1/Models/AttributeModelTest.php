<?php

use App\Models\Attribute;
use App\Models\Product;

it('can get relationship with product model', function () {
    $attribute = Attribute::factory()->create();
    $products = Product::factory()->count(5)->create();

    foreach ($products as $product) {
        $product->attributes()->attach($attribute, ['value' => $attribute->type]);
    }

    $this->assertEquals($attribute->products()->count(), 5);
});

it('can query variant attribute models', function () {
    Attribute::create([
        'name' => 'Color',
        'slug' => 'color',
        'type' => 'text',
        'is_variant' => true,
    ]);
    Attribute::create([
        'name' => 'Size',
        'slug' => 'size',
        'type' => 'text',
        'is_variant' => false,
    ]);

    $this->assertEquals(1, Attribute::variant()->count());
});

it('can query filterable attribute models', function () {
    Attribute::create([
        'name' => 'Color',
        'slug' => 'color',
        'type' => 'text',
        'is_filterable' => true,
    ]);
    Attribute::create([
        'name' => 'Size',
        'slug' => 'size',
        'type' => 'text',
        'is_filterable' => false,
    ]);

    $this->assertEquals(1, Attribute::filterable()->count());
});
