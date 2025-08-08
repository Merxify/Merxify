<?php

use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('does not allow to view all categories without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $response = $this->actingAs($user)->get('/api/v1/categories');

    $response->assertStatus(403);
});

it('allows to view all categories with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $response = $this->actingAs($user)->get('/api/v1/categories');

    $response->assertStatus(200);
});

it('does not allow to view single category without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    Category::factory()->create();

    $response = $this->actingAs($user)->get('/api/v1/categories/1');

    $response->assertStatus(403);
});

it('allows to view single category with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    Category::factory()->create();

    $response = $this->actingAs($user)->get('/api/v1/categories/1');

    $response->assertStatus(200);
});

it('does not allow to store new category without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $response = $this->actingAs($user)->post('/api/v1/categories', [
        'name' => 'newCategory',
    ]);

    $response->assertStatus(403);
});

it('allows to store new category with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $response = $this->actingAs($user)->postJson('/api/v1/categories', [
        'name' => 'newCategory',
    ]);

    $response->assertStatus(201);
});

it('does not allow to update category without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $category = Category::factory()->create([
        'name' => 'newCategory',
    ]);

    $response = $this->actingAs($user)->patch('/api/v1/categories/'.$category->id, [
        'name' => 'anotherCategory',
    ]);

    $response->assertStatus(403);
});

it('allows to update category with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $category = Category::factory()->create([
        'name' => 'newCategory',
    ]);

    $response = $this->actingAs($user)->patch('/api/v1/categories/'.$category->id, [
        'name' => 'anotherCategory',
    ]);

    $response->assertStatus(200);
});

it('does not allow to delete category without proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'customer@example.com')->first(),
        ['*'],
    );

    $category = Category::factory()->create();

    $response = $this->actingAs($user)->delete('/api/v1/categories/'.$category->id);

    $response->assertStatus(403);
});

it('allows to delete category with proper permission', function () {
    $user = Sanctum::actingAs(
        User::where('email', 'admin@example.com')->first(),
        ['*'],
    );

    $category = Category::factory()->create();

    $response = $this->actingAs($user)->delete('/api/v1/categories/'.$category->id);

    $response->assertStatus(200);
});
