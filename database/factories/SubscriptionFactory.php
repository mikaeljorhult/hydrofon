<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $subscribables = [
            \App\Models\Resource::class,
            \App\Models\User::class,
        ];

        $subscribableType = $this->faker->randomElement($subscribables);
        $subscribable = app()->make($subscribableType)->factory()->create();

        return [
            'subscribable_id'   => $subscribable->id,
            'subscribable_type' => $subscribableType,
        ];
    }
}
