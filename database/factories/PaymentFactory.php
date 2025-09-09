<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider' => $this->faker->randomElement(['stripe', 'paypal']),
            'amount' => $this->faker->randomFloat(2, 20, 2000),
            'status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'transaction_id' => strtoupper($this->faker->bothify('TXN-#######')),
        ];
    }
}
