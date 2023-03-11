<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => 'broken',
            'model_id' => \App\Models\Resource::factory(),
            'model_type' => \App\Models\Resource::class,
            'created_by_id' => \App\Models\User::factory(),
        ];
    }

    public function broken()
    {
        return $this->state(['name' => 'broken']);
    }

    public function dirty()
    {
        return $this->state(['name' => 'dirty']);
    }

    public function inrepair()
    {
        return $this->state(['name' => 'inrepair']);
    }

    public function missing()
    {
        return $this->state(['name' => 'missing']);
    }
}
