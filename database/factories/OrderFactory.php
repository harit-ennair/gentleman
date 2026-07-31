<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
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
        $date = fake()->dateTimeThisYear();
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . $date->format('Ymd') . '-' . fake()->unique()->numberBetween(1000, 9999),
            'total' => fake()->randomFloat(2, 10, 500),
            'status' => OrderStatus::Pending,
            'payment_status' => PaymentStatus::Pending,
            'order_date' => $date,
            'notes' => fake()->sentence(),
        ];
    }
}
