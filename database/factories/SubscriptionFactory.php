<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $subscribables = [
            \App\Models\Resource::class,
            \App\Models\User::class,
        ];

        $subscribableType = $this->faker->randomElement($subscribables);

        return [
            'subscribable_id' => app()->make($subscribableType)->factory(),
            'subscribable_type' => $subscribableType,
        ];
    }

    /**
     * Subscription of a resource.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function resource(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'subscribable_id' => \App\Models\Resource::factory(),
                'subscribable_type' => \App\Models\Resource::class,
            ];
        });
    }

    /**
     * Subscription of a user.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function user(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'subscribable_id' => \App\Models\User::factory(),
                'subscribable_type' => \App\Models\User::class,
            ];
        });
    }
}
