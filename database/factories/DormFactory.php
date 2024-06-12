<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dorm>
 */
class DormFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'user_id' => fake()->numberBetween(0, 10),
            'name' => fake()->name(),
            'images' => fake()->imageUrl(),
            'longtitude' => fake()->longitude(),
            'latitude' => fake()->latitude(),
            'capacity' => fake()->numberBetween(0, 999),
            'phone' => fake()->phoneNumber(),
            'type' => fake()->randomElement(['Male', 'Female', 'Mixed']),
            'description' => fake()->text(),
        ];
    }
}
