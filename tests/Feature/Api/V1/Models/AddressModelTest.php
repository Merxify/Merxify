<?php

use App\Models\Address;
use App\Models\User;

it('can create address model', function () {
    Address::factory()->create();

    $this->assertDatabaseCount('addresses', 1);
});

it('can get relationship with user model', function () {
    $user = User::factory()->create();

    $address = Address::factory()->create(['user_id' => $user->id]);

    $this->assertEquals($address->user->id, $user->id);
});

it('can query default address models', function () {
    Address::factory()->count(5)->create(['is_default' => false]);
    Address::factory()->count(5)->create(['is_default' => true]);

    $this->assertEquals(5, Address::default()->count());
});

it('can query billing address models', function () {
    Address::factory()->count(5)->create(['type' => 'billing']);
    Address::factory()->count(2)->create(['type' => 'shipping']);
    Address::factory()->count(3)->create(['type' => 'both']);

    $this->assertEquals(8, Address::billing()->count());
});

it('can query shipping address models', function () {
    Address::factory()->count(5)->create(['type' => 'billing']);
    Address::factory()->count(2)->create(['type' => 'shipping']);
    Address::factory()->count(3)->create(['type' => 'both']);

    $this->assertEquals(5, Address::shipping()->count());
});

it('can get full name attribute', function () {
    $address = Address::factory()->create([
        'first_name' => 'Test',
        'last_name' => 'User',
    ]);

    $this->assertEquals('Test User', $address->full_name);
});

it('can get full address attribute', function () {
    $address = Address::factory()->create([
        'address_line_1' => 'Address 1',
        'address_line_2' => 'Address 2',
        'city' => 'City',
        'state' => 'State',
        'postal_code' => '12345',
    ]);

    $this->assertEquals('Address 1, Address 2, City, State 12345', $address->full_address);
});
