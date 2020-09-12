<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Resource::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->city,
            'description' => $this->faker->sentence,
            'is_facility' => false,
        ];
    }

    public function facility()
    {
        return $this->state(['is_facility' => true]);
    }
}
