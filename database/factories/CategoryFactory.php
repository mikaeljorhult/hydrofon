<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->country(),
        ];
    }

    public function child()
    {
        return $this->state([
            'parent_id' => function () {
                return Category::factory()->create()->id;
            },
        ]);
    }
}
