<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin2@merxify.app',
        'group' => 'admin',
    ]);
});

it('can show user cart', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );
    $response = $this
        ->actingAs($user)
        ->getJson('/api/v1/admin/cart');

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('message')
            ->has('status')
        )
        ->assertStatus(200);
});

it('can add item to user cart', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $product = Product::factory()->create();

    $response = $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('message')
            ->has('status')
        )
        ->assertStatus(200);
});

it('can add update item quantity to user cart', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $product = Product::factory()->create();

    $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

    $response = $this
        ->actingAs($user)
        ->putJson('/api/v1/admin/cart/items/1', [
            'quantity' => 4,
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('message')
            ->has('status')
        )
        ->assertStatus(200);
});

it('can add remove item from user cart', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $product = Product::factory()->create();

    $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

    $response = $this
        ->actingAs($user)
        ->deleteJson('/api/v1/admin/cart/items/1');

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('message')
            ->has('status')
        )
        ->assertStatus(200);
});

it('can clear user cart', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $product = Product::factory()->create();

    $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

    $response = $this
        ->actingAs($user)
        ->deleteJson('/api/v1/admin/cart/clear');

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('message')
            ->has('status')
        )
        ->assertStatus(200);
});

it('can show validation errors', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );
    $product = Product::factory()->create();

    $response = $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/cart/items', [
            'product_id' => $product->id,
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('message', 'Validation failed')
            ->etc()
        )
        ->assertStatus(422);

    $response = $this
        ->actingAs($user)
        ->putJson('/api/v1/admin/cart/items/1');

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('message', 'Validation failed')
            ->etc()
        )
        ->assertStatus(422);

    $response = $this
        ->actingAs($user)
        ->putJson('/api/v1/admin/cart/items/10', [
            'quantity' => 2,
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('message', 'Cart item not found')
            ->etc()
        )
        ->assertStatus(404);

    $response = $this
        ->actingAs($user)
        ->deleteJson('/api/v1/admin/cart/items/10');

    $response
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('message', 'Cart item not found')
            ->etc()
        )
        ->assertStatus(404);
});
