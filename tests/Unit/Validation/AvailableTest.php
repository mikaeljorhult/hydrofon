<?php

namespace Tests\Unit\Validation;

use App\Models\Booking;
use App\Models\Resource;
use App\Rules\Available;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailableTest extends TestCase
{
    use RefreshDatabase;

    private function ruleValidates(Available $rule, $attribute, $value)
    {
        $passes = true;

        $this->app->call([$rule, 'validate'], [
            'attribute' => $attribute,
            'value' => $value,
            'fail' => static function () use (&$passes): void {
                $passes = false;
            },
        ]);

        return $passes;
    }

    /**
     * Two bookings can't occupy the same resource and time.
     */
    public function testSameResourceAndTime(): void
    {
        $booking = Booking::factory()->create();
        $rule = new Available($booking->start_time, $booking->end_time);

        $this->assertFalse($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }

    /**
     * Two bookings can occupy the same time as long as resources are different.
     */
    public function testSameTimeDifferentResources(): void
    {
        $booking = Booking::factory()->create();
        $rule = new Available($booking->start_time, $booking->end_time);

        $this->assertTrue($this->ruleValidates($rule, 'resource_id', Resource::factory()->create()->id));
    }

    /**
     * A booking can be ignored from availability to allow for updates.
     */
    public function testOneBookingCanBeIgnored(): void
    {
        $booking = Booking::factory()->create();
        $rule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }

    /**
     * A booking can start when another ends.
     */
    public function testBookingCanStartWhenAnotherEnds(): void
    {
        $previous = Booking::factory()->create([
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time' => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $booking = Booking::factory()->create([
            'resource_id' => $previous->resource_id,
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time' => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $rule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }

    /**
     * A booking can end when another start.
     */
    public function testBookingCanEndWhenAnotherStart(): void
    {
        $previous = Booking::factory()->create([
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time' => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $booking = Booking::factory()->create([
            'resource_id' => $previous->resource_id,
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time' => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $rule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap a whole other booking.
     */
    public function testBookingCannotOverlapAWholeOtherBooking(): void
    {
        $previous = Booking::factory()->create([
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time' => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $booking = Booking::factory()->create([
            'resource_id' => $previous->resource_id,
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time' => Carbon::parse('2017-01-01 15:00:00'),
        ]);

        $rule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap start of other booking.
     */
    public function testBookingCannotOverlapStartOfAnotherBooking(): void
    {
        $previous = Booking::factory()->create([
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time' => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $booking = Booking::factory()->create([
            'resource_id' => $previous->resource_id,
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time' => Carbon::parse('2017-01-01 13:15:00'),
        ]);

        $rule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap end of another booking.
     */
    public function testBookingCannotOverlapEndOfAnotherBooking(): void
    {
        $previous = Booking::factory()->create([
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time' => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $booking = Booking::factory()->create([
            'resource_id' => $previous->resource_id,
            'start_time' => Carbon::parse('2017-01-01 12:45:00'),
            'end_time' => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $rule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap middle of another booking.
     */
    public function testBookingCannotOverlapMiddleOfAnotherBooking(): void
    {
        $previous = Booking::factory()->create([
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time' => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $booking = Booking::factory()->create([
            'resource_id' => $previous->resource_id,
            'start_time' => Carbon::parse('2017-01-01 12:15:00'),
            'end_time' => Carbon::parse('2017-01-01 12:45:00'),
        ]);

        $rule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($this->ruleValidates($rule, 'resource_id', $booking->resource_id));
    }
}
