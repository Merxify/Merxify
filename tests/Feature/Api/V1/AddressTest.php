<?php

use App\Models\Address;
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

it('can show all addresses', function () {
    $this->seed(DatabaseSeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );
    $response = $this
        ->actingAs($user)
        ->getJson('/api/v1/admin/addresses');

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 153)
        )
        ->assertStatus(200);
});

it('can show single address', function () {
    $this->seed(DatabaseSeeder::class);

    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $address = Address::first();

    $response = $this
        ->actingAs($user)
        ->getJson("/api/v1/admin/addresses/$address->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5)
            ->where('data.type', 'address')
            ->where('data.id', $address->id)
            ->where('data.attributes.first_name', $address->first_name)
        )
        ->assertStatus(200);
});

it('can create new address', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $response = $this
        ->actingAs($user)
        ->postJson('/api/v1/admin/addresses', [
            'user_id' => $this->user->id,
            'type' => 'both',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address_line_1' => 'Some random street address',
            'city' => 'Berlin',
            'state' => 'Berlin State',
            'postal_code' => '12345',
            'country_code' => 'DE',
            'is_default' => true,
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5)
            ->where('data.type', 'address')
            ->where('data.id', 1)
            ->where('data.attributes.address_line_1', 'Some random street address')
        )
        ->assertStatus(201);
});

it('can update an address', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $address = Address::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patchJson("/api/v1/admin/addresses/$address->id", [
            'first_name' => 'Jane',
        ]);

    $response
        ->assertJson(fn (AssertableJson $json) => $json->has('data', 5)
            ->where('data.type', 'address')
            ->where('data.id', $address->id)
            ->where('data.attributes.first_name', 'Jane')
            ->where('data.attributes.last_name', $address->last_name)
        )
        ->assertStatus(200);
});

it('can delete an address', function () {
    $user = Sanctum::actingAs(
        $this->user,
        ['*'],
    );

    $address = Address::factory()->create();

    $response = $this
        ->actingAs($user)
        ->deleteJson("/api/v1/admin/addresses/$address->id");

    $response
        ->assertJson(fn (AssertableJson $json) => $json->hasAll('data', 'message', 'status')
            ->where('message', 'Address deleted successfully.')
        )
        ->assertStatus(200);

    $this->assertDatabaseMissing('addresses', [
        'id' => $address->id,
    ]);
});
