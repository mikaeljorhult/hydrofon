<?php

namespace Database\Factories;

use App\Booking;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CheckoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Checkout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'booking_id' => Booking::factory()->create()->id,
            'user_id'    => User::factory()->create()->id,
        ];
    }
}
