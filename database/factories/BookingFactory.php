<?php

namespace Database\Factories;

use App\Resource;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'       => $user_id = User::factory()->create()->id,
            'resource_id'   => Resource::factory()->create()->id,
            'created_by_id' => $user_id,
            'start_time'    => now()->addHours(1),
            'end_time'      => now()->addHours(3),
        ];
    }

    public function current()
    {
        return $this->state(['start_time' => now()->subHour(), 'end_time' => now()->addHour()]);
    }

    public function past()
    {
        return $this->state(['start_time' => now()->subYear(), 'end_time' => now()->subYear()->addHour()]);
    }

    public function future()
    {
        return $this->state(['start_time' => now()->addYear(), 'end_time' => now()->addYear()->addHour()]);
    }
}
