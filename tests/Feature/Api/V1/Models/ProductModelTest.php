<?php

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

it('can get relationship with category model', function () {
    $categories = Category::factory()->count(3)->create();
    $product = Product::factory()->create();
    $product->categories()->attach($categories);

    $this->assertEquals($product->categories()->count(), 3);
});

it('can get relationship with attribute model', function () {
    $attributes = Attribute::factory(3)->create();
    $product = Product::factory()->create();

    foreach ($attributes as $attribute) {
        $product->attributes()->attach($attribute, ['value' => $attribute->type]);
    }

    $this->assertEquals($product->attributes()->count(), 3);
});

it('can get relationship with product variant model', function () {
    $product = Product::factory()->create();
    ProductVariant::factory(5)->create([
        'product_id' => $product->id,
    ]);

    $this->assertEquals($product->variants()->count(), 5);
});

it('can query active product model', function () {
    Product::factory()->count(5)->create([
        'status' => 'active',
    ]);

    Product::factory()->count(3)->create([
        'status' => 'draft',
    ]);

    $this->assertEquals(5, Product::active()->count());
});

it('can query simple product model', function () {
    Product::factory()->count(5)->create([
        'type' => 'simple',
    ]);

    Product::factory()->count(3)->create([
        'type' => 'digital',
    ]);

    $this->assertEquals(5, Product::simple()->count());
});

it('can query configurable product model', function () {
    Product::factory()->count(5)->create([
        'type' => 'configurable',
    ]);

    Product::factory()->count(3)->create([
        'type' => 'digital',
    ]);

    $this->assertEquals(5, Product::configurable()->count());
});
