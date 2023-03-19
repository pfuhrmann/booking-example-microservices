<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date_arrival' => fake()->date(),
            'date_departure' => fake()->date(),
            'time_arrival' => fake()->date('H:i'),
            'time_departure' => fake()->date('H:i'),
        ];
    }
}
