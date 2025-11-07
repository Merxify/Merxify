<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $billingAddress = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'company' => $this->faker->boolean(30) ? $this->faker->company() : null,
            'address_line_1' => $this->faker->streetAddress(),
            'address_line_2' => $this->faker->boolean(30) ? $this->faker->secondaryAddress() : null,
            'city' => $this->faker->city(),
            'state_province' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country_code' => $this->faker->countryCode(),
        ];

        return [
            'user_id' => $this->faker->boolean(80) ? User::factory() : null,
            'order_number' => 'ORD-'.strtoupper($this->faker->unique()->bothify('##??##??')),
            'status' => $this->faker->randomElement(['pending', 'processing', 'shipped', 'delivered', 'cancelled']),
            'currency' => $this->faker->randomElement(['USD', 'EUR', 'GBP']),
            'total_amount' => $this->faker->randomFloat(2, 10, 500),
            'billing_address' => $billingAddress,
            'shipping_address' => $this->faker->boolean(70) ? $billingAddress : [
                'first_name' => $this->faker->firstName(),
                'last_name' => $this->faker->lastName(),
                'company' => null,
                'address_line_1' => $this->faker->streetAddress(),
                'address_line_2' => null,
                'city' => $this->faker->city(),
                'state_province' => $this->faker->state(),
                'postal_code' => $this->faker->postcode(),
                'country_code' => $this->faker->countryCode(),
            ],
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->boolean(30) ? $this->faker->paragraph() : null,
            'shipped_at' => $this->faker->boolean(40) ? $this->faker->dateTimeBetween('-30 days', 'now') : null,
            'delivered_at' => $this->faker->boolean(20) ? $this->faker->dateTimeBetween('-15 days', 'now') : null,

        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'shipped_at' => null,
            'delivered_at' => null,
        ]);
    }

    public function shipped(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'shipped',
            'shipped_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'delivered_at' => null,
        ]);
    }

    public function delivered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'delivered',
            'shipped_at' => $this->faker->dateTimeBetween('-15 days', '-5 days'),
            'delivered_at' => $this->faker->dateTimeBetween('-5 days', 'now'),
        ]);
    }
}
