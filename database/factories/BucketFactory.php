<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BucketFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country(),
        ];
    }
}
