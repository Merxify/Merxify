<?php

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );
});

it('shows all categories', function () {
    Category::factory(10)->create();

    $response = $this->actingAs($this->user)->get('/api/v1/categories');

    $response
        ->assertStatus(200)
        ->assertJsonCount(10, 'data');
});

it('can paginate categories based on user query', function () {
    Category::factory(10)->create();

    $response = $this->actingAs($this->user)->get('/api/v1/categories?per_page=2');
    $response
        ->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJson([
            'links' => [],
        ]);
});

it('can show single category', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->get('/api/v1/categories/'.$category->id);

    $response
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
            ],
        ]);
});

it('can delete category', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->delete('/api/v1/categories/'.$category->id);

    $response->assertStatus(200);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

it('can create category', function () {
    $response = $this->actingAs($this->user)->post('/api/v1/categories', [
        'name' => 'newCategory',
    ]);

    $response
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                'name' => 'newCategory',
            ],
        ]);
});

it('can update category', function () {
    $category = Category::factory()->create();

    $response = $this->actingAs($this->user)->patch('/api/v1/categories/'.$category->id, [
        'name' => 'another Category',
    ]);

    $response
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'another Category',
            ],
        ]);
});
