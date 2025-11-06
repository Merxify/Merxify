<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'type' => $this->faker->randomElement(['billing', 'shipping', 'both']),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'company' => $this->faker->boolean(30) ? $this->faker->company() : null,
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => $this->faker->boolean(30) ? $this->faker->secondaryAddress() : null,
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country_code' => $this->faker->countryCode(),
            'is_default' => false,
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }

    public function billing(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'billing',
        ]);
    }

    public function shipping(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'shipping',
        ]);
    }
}
