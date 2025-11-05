<?php

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Site Admin',
        'email' => 'admin@merxify.app',
        'group' => 'admin',
    ]);
});

it('can show all categories', function () {
    $this->seed(CategorySeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );
    $response = $this
        ->actingAs($user)
        ->getJson('/api/v1/admin/categories');

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 20)
        )
        ->assertStatus(200);
});

it('can show single category', function () {
    $this->seed(CategorySeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $category = Category::first();

    $response = $this
        ->actingAs($user)
        ->getJson("/api/v1/admin/categories/$category->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
            ->where('data.type', 'category')
            ->where('data.id', $category->id)
            ->where('data.attributes.slug', $category->slug)
        )
        ->assertStatus(200);
});

it('can create new category', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $response = $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/categories', [
            'name' => 'Category 1',
            'slug' => 'category-1',
            'description' => 'Category 1 description',
            'meta_title' => 'Category 1 Meta Title',
            'meta_description' => 'Category 1 Meta Description',
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
            ->where('data.type', 'category')
            ->where('data.id', 1)
            ->where('data.attributes.slug', 'category-1')
        )
        ->assertStatus(201);
});

it('can update a category', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
    ]);

    $response = $this
        ->actingAs($user)
        ->patchJson("/api/v1/admin/categories/$category->id", [
            'name' => 'Category 2',
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
            ->where('data.type', 'category')
            ->where('data.id', $category->id)
            ->where('data.attributes.name', 'Category 2')
        )
        ->assertStatus(200);
});

it('can delete a category', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $category = Category::create([
        'name' => 'Category 1',
        'slug' => 'category-1',
        'description' => 'Category 1 description',
        'meta_title' => 'Category 1 Meta Title',
        'meta_description' => 'Category 1 Meta Description',
    ]);

    $response = $this
        ->actingAs($user)
        ->deleteJson("/api/v1/admin/categories/$category->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->hasAll('data', 'message', 'status')
            ->where('message', 'Category deleted successfully.')
        )
        ->assertStatus(200);

    $this->assertDatabaseMissing('categories', [
        'id' => $category->id,
    ]);
});
