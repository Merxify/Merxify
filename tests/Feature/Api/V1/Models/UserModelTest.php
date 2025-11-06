<?php

use App\Models\Address;
use App\Models\User;

it('can create a new user', function () {
    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
    ]);

    $this->assertDatabaseCount('users', 1);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
    ]);
});

it('can update a user', function () {
    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
    ]);

    $user->update([
        'first_name' => 'Jane',
    ]);

    $this->assertDatabaseCount('users', 1);

    $this->assertDatabaseHas('users', [
        'first_name' => 'Jane',
        'email' => 'john@doe.com',
    ]);
});

it('can delete a user', function () {
    $user = User::create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@doe.com',
        'password' => 'password',
    ]);

    $user->delete();

    $this->assertDatabaseCount('users', 0);

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('can get addresses relationship', function () {
    $user = User::factory()->create();

    $address = Address::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->assertEquals($user->addresses()->first()->id, $address->id);
});

it('can get users full name', function () {
    $user = User::factory()->create();

    $this->assertEquals($user->first_name.' '.$user->last_name, $user->full_name);
});

it('can check if user is admin', function () {
    $admin = User::factory()->create([
        'group' => 'admin',
    ]);

    $customer = User::factory()->create([
        'group' => 'customer',
    ]);

    $this->assertTrue($admin->isAdmin());
    $this->assertFalse($customer->isAdmin());
});

it('can check if user is customer', function () {
    $admin = User::factory()->create([
        'group' => 'admin',
    ]);

    $customer = User::factory()->create([
        'group' => 'customer',
    ]);

    $this->assertFalse($admin->isCustomer());
    $this->assertTrue($customer->isCustomer());
});

it('can get all customers', function () {
    User::factory()->count(10)->create([
        'group' => 'customer',
    ]);

    User::factory()->count(3)->create([
        'group' => 'admin',
    ]);

    $this->assertEquals(User::customers()->count(), 10);
});

it('can get all active users', function () {
    User::factory()->count(7)->create([
        'is_active' => true,
    ]);

    User::factory()->count(3)->create([
        'is_active' => false,
    ]);

    $this->assertEquals(User::active()->count(), 7);
});
