<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 150),
            'duration' => fake()->randomElement([15, 30, 45, 60, 90]),
            'image_path' => fake()->imageUrl(),
            'is_active' => true,
        ];
    }
}
