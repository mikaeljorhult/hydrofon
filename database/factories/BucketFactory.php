<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BucketFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Bucket::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'name' => $this->faker->country,
    ];
    }
}
