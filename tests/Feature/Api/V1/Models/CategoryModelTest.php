<?php

use App\Models\Category;
use App\Models\Product;

it('can create a category', function () {
    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'parent_id' => null,
    ]);

    $this->assertDatabaseCount('categories', 1);

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
    ]);
});

it('can update a category', function () {
    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'parent_id' => null,
    ]);

    $category->update([
        'name' => 'Category 2',
    ]);

    $this->assertDatabaseCount('categories', 1);

    $this->assertDatabaseHas('categories', [
        'name' => 'Category 2',
    ]);
});

it('can delete a category', function () {
    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'parent_id' => null,
    ]);

    $category->delete();

    $this->assertDatabaseCount('categories', 0);

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});

it('can get category-products relationship', function () {
    $category1 = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'parent_id' => null,
    ]);

    $category2 = Category::create([
        'name' => 'Category 2',
        'slug' => 'category-2',
        'parent_id' => null,
    ]);

    Product::factory(7)->create([
        'category_id' => $category1->id,
    ]);

    Product::factory(3)->create([
        'category_id' => $category2->id,
    ]);

    $this->assertEquals($category1->products()->count(), 7);
});

it('can get parent category relationship', function () {
    $parent = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'parent_id' => null,
    ]);

    $category = Category::create([
        'name' => 'Category 2',
        'slug' => 'category-2',
        'parent_id' => $parent->id,
    ]);

    $this->assertEquals($category->parent->id, $parent->id);
});

it('can get children category relationship', function () {
    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'parent_id' => null,
    ]);

    $children = Category::create([
        'name' => 'Category 2',
        'slug' => 'category-2',
        'parent_id' => $category->id,
    ]);

    $this->assertEquals($category->children->first()->id, $children->id);
});
