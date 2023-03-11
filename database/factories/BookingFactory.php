<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\User;
use App\States\Approved;
use App\States\AutoApproved;
use App\States\CheckedIn;
use App\States\CheckedOut;
use App\States\Pending;
use App\States\Rejected;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => $user_id = User::factory()->create()->id,
            'resource_id' => Resource::factory()->create()->id,
            'created_by_id' => $user_id,
            'start_time' => now()->addHours(1),
            'end_time' => now()->addHours(3),
        ];
    }

    public function current()
    {
        return $this->state([
            'start_time' => now()->subHour(),
            'end_time' => now()->addHour(),
        ]);
    }

    public function past()
    {
        return $this->state([
            'start_time' => now()->subYear(),
            'end_time' => now()->subYear()->addHour(),
        ]);
    }

    public function future()
    {
        return $this->state([
            'start_time' => now()->addYear(),
            'end_time' => now()->addYear()->addHour(),
        ]);
    }

    public function pending()
    {
        return $this->state(['state' => Pending::class]);
    }

    public function approved()
    {
        return $this->state(['state' => Approved::class]);
    }

    public function autoapproved()
    {
        return $this->state(['state' => AutoApproved::class]);
    }

    public function rejected()
    {
        return $this->state(['state' => Rejected::class]);
    }

    public function checkedout()
    {
        return $this->state(['state' => CheckedOut::class]);
    }

    public function checkedin()
    {
        return $this->state(['state' => CheckedIn::class]);
    }

    public function overdue()
    {
        return $this->state([
            'start_time' => now()->subHour(),
            'end_time' => now()->subMinute(),
            'state' => CheckedOut::class,
        ]);
    }
}
