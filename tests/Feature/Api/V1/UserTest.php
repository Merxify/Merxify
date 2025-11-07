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
        ->assertStatus(200)
        ->assertExactJsonStructure(['data'])
        ->assertJsonPath('data.email', $user1->email)
        ->assertJsonPath('data.first_name', $user1->first_name);
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
        ->assertStatus(201)
        ->assertExactJsonStructure(['data'])
        ->assertJsonPath('data.email', 'john@doe.com')
        ->assertJsonPath('data.first_name', 'John');
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
        ->assertStatus(200)
        ->assertExactJsonStructure(['data'])
        ->assertJsonPath('data.email', 'john@doe.com')
        ->assertJsonPath('data.first_name', 'Jane');
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
