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

    /**
     * Two bookings can't occupy the same resource and time.
     *
     * @return void
     */
    public function testSameResourceAndTime(): void
    {
        $booking = Booking::factory()->create();
        $availableRule = new Available($booking->start_time, $booking->end_time);

        $this->assertFalse($availableRule->passes('resource_id', $booking->resource_id));
    }

    /**
     * Two bookings can occupy the same time as long as resources are different.
     *
     * @return void
     */
    public function testSameTimeDifferentResources(): void
    {
        $booking = Booking::factory()->create();
        $availableRule = new Available($booking->start_time, $booking->end_time);

        $this->assertTrue($availableRule->passes('resource_id', Resource::factory()->create()->id));
    }

    /**
     * A booking can be ignored from availability to allow for updates.
     *
     * @return void
     */
    public function testOneBookingCanBeIgnored(): void
    {
        $booking = Booking::factory()->create();
        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($availableRule->passes('resource_id', $booking->resource_id));
    }

    /**
     * A booking can start when another ends.
     *
     * @return void
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

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($availableRule->passes('resource_id', $booking->resource_id));
    }

    /**
     * A booking can end when another start.
     *
     * @return void
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

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($availableRule->passes('resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap a whole other booking.
     *
     * @return void
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

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap start of other booking.
     *
     * @return void
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

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap end of another booking.
     *
     * @return void
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

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('resource_id', $booking->resource_id));
    }

    /**
     * A booking cannot overlap middle of another booking.
     *
     * @return void
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

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('resource_id', $booking->resource_id));
    }
}
