<?php

use App\Models\Category;

it('can create a new category', function () {
    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
        'meta_keywords' => 'Category 1 Meta Keywords',
        'is_active' => true,
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
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
        'meta_keywords' => 'Category 1 Meta Keywords',
        'is_active' => true,
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
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
        'meta_keywords' => 'Category 1 Meta Keywords',
        'is_active' => true,
        'parent_id' => null,
    ]);

    $category->delete();

    $this->assertDatabaseCount('categories', 0);

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});

it('can get parent category relationship', function () {
    $parent = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
        'meta_keywords' => 'Category 1 Meta Keywords',
        'is_active' => true,
        'parent_id' => null,
    ]);

    $category = Category::create([
        'name' => 'Category 2',
        'slug' => 'category-2',
        'description' => 'Category 2 description',
        'meta_title' => 'Category 2 Meta Title',
        'meta_description' => 'Category 2 Meta Description',
        'meta_keywords' => 'Category 2 Meta Keywords',
        'is_active' => true,
        'parent_id' => $parent->id,
    ]);

    $this->assertEquals($category->parent->id, $parent->id);
});

it('can get children category relationship', function () {
    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
        'meta_keywords' => 'Category 1 Meta Keywords',
        'is_active' => true,
        'parent_id' => null,
    ]);

    $children = Category::create([
        'name' => 'Category 2',
        'slug' => 'category-2',
        'description' => 'Category 2 description',
        'meta_title' => 'Category 2 Meta Title',
        'meta_description' => 'Category 2 Meta Description',
        'meta_keywords' => 'Category 2 Meta Keywords',
        'is_active' => true,
        'parent_id' => $category->id,
    ]);

    $this->assertEquals($category->children->first()->id, $children->id);
});
