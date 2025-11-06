<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
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

it('can show all users', function () {
    $this->seed(DatabaseSeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );
    $response = $this
        ->actingAs($user)
        ->getJson('/api/v1/admin/users');

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 52)
        )
        ->assertStatus(200);
});

it('can show single user', function () {
    $this->seed(DatabaseSeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $user1 = User::first();

    $response = $this
        ->actingAs($user)
        ->getJson("/api/v1/admin/users/$user1->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
            ->where('data.type', 'user')
            ->where('data.id', $user1->id)
            ->where('data.attributes.first_name', $user1->first_name)
        )
        ->assertStatus(200);
});

it('can create new user', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $response = $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/users', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
            ->where('data.type', 'user')
            ->where('data.id', 2)
            ->where('data.attributes.email', 'john@doe.com')
        )
        ->assertStatus(201);
});

it('can update a user', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $newUser = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
    ]);

    $response = $this
        ->actingAs($user)
        ->patchJson("/api/v1/admin/users/$newUser->id", [
            'first_name' => 'Jane',
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 4)
            ->where('data.type', 'user')
            ->where('data.id', $newUser->id)
            ->where('data.attributes.first_name', 'Jane')
            ->where('data.attributes.email', $newUser->email)
        )
        ->assertStatus(200);
});

it('can delete a user', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $newUser = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
    ]);

    $response = $this
        ->actingAs($user)
        ->deleteJson("/api/v1/admin/users/$newUser->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->hasAll('data', 'message', 'status')
            ->where('message', 'User deleted successfully.')
        )
        ->assertStatus(200);

    $this->assertDatabaseMissing('users', [
        'id' => $newUser->id,
    ]);
});
