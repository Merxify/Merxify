<?php

use App\Models\Product;
use App\Models\ProductVariant;

it('can get relationship with product model', function () {
    $product = Product::factory()->create();
    $productVariant = ProductVariant::factory()->create([
        'product_id' => $product->id,
    ]);

    $this->assertEquals($productVariant->product->id, $product->id);
});

it('can query active product variant models', function () {
    ProductVariant::factory()->count(5)->create([
        'is_active' => true,
    ]);

    ProductVariant::factory()->count(3)->create([
        'is_active' => false,
    ]);

    $this->assertEquals(5, ProductVariant::active()->count());
});
