<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'pending',
            'model_id' => \App\Models\Booking::factory(),
            'model_type' => \App\Models\Booking::class,
            'created_by_id' => \App\Models\User::factory(),
        ];
    }

    public function approved()
    {
        return $this->state(['name' => 'approved']);
    }

    public function rejected()
    {
        return $this->state(['name' => 'rejected']);
    }

    public function revoked()
    {
        return $this->state(['name' => 'revoked']);
    }
}
