<?php

namespace Tests\Unit\Validation;

use Carbon\Carbon;
use Hydrofon\Booking;
use Hydrofon\Object;
use Hydrofon\Rules\Available;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AvailableTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Two bookings can't occupy the same object and time.
     *
     * @return void
     */
    public function testSameObjectAndTime()
    {
        $booking = factory(Booking::class)->create();
        $availableRule = new Available($booking->start_time, $booking->end_time);

        $this->assertFalse($availableRule->passes('object_id', $booking->object_id));
    }

    /**
     * Two bookings can occupy the same time as long as objects are different.
     *
     * @return void
     */
    public function testSameTimeDifferentObjects()
    {
        $booking = factory(Booking::class)->create();
        $availableRule = new Available($booking->start_time, $booking->end_time);

        $this->assertTrue($availableRule->passes('object_id', factory(Object::class)->create()->id));
    }

    /**
     * A booking can be ignored from availability to allow for updates.
     *
     * @return void
     */
    public function testOneBookingCanBeIgnored()
    {
        $booking = factory(Booking::class)->create();
        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($availableRule->passes('object_id', $booking->object_id));
    }

    /**
     * A booking can start when another ends.
     *
     * @return void
     */
    public function testBookingCanStartWhenAnotherEnds()
    {
        $previous = factory(Booking::class)->create([
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $booking = factory(Booking::class)->create([
            'object_id'  => $previous->object_id,
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($availableRule->passes('object_id', $booking->object_id));
    }

    /**
     * A booking can end when another start.
     *
     * @return void
     */
    public function testBookingCanEndWhenAnotherStart()
    {
        $previous = factory(Booking::class)->create([
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $booking = factory(Booking::class)->create([
            'object_id'  => $previous->object_id,
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertTrue($availableRule->passes('object_id', $booking->object_id));
    }

    /**
     * A booking cannot overlap a whole other booking.
     *
     * @return void
     */
    public function testBookingCannotOverlapAWholeOtherBooking()
    {
        $previous = factory(Booking::class)->create([
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $booking = factory(Booking::class)->create([
            'object_id'  => $previous->object_id,
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 15:00:00'),
        ]);

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('object_id', $booking->object_id));
    }

    /**
     * A booking cannot overlap start of other booking.
     *
     * @return void
     */
    public function testBookingCannotOverlapStartOfAnotherBooking()
    {
        $previous = factory(Booking::class)->create([
            'start_time' => Carbon::parse('2017-01-01 13:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $booking = factory(Booking::class)->create([
            'object_id'  => $previous->object_id,
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 13:15:00'),
        ]);

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('object_id', $booking->object_id));
    }

    /**
     * A booking cannot overlap end of another booking.
     *
     * @return void
     */
    public function testBookingCannotOverlapEndOfAnotherBooking()
    {
        $previous = factory(Booking::class)->create([
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $booking = factory(Booking::class)->create([
            'object_id'  => $previous->object_id,
            'start_time' => Carbon::parse('2017-01-01 12:45:00'),
            'end_time'   => Carbon::parse('2017-01-01 14:00:00'),
        ]);

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('object_id', $booking->object_id));
    }

    /**
     * A booking cannot overlap middle of another booking.
     *
     * @return void
     */
    public function testBookingCannotOverlapMiddleOfAnotherBooking()
    {
        $previous = factory(Booking::class)->create([
            'start_time' => Carbon::parse('2017-01-01 12:00:00'),
            'end_time'   => Carbon::parse('2017-01-01 13:00:00'),
        ]);

        $booking = factory(Booking::class)->create([
            'object_id'  => $previous->object_id,
            'start_time' => Carbon::parse('2017-01-01 12:15:00'),
            'end_time'   => Carbon::parse('2017-01-01 12:45:00'),
        ]);

        $availableRule = new Available($booking->start_time, $booking->end_time, $booking->id);

        $this->assertFalse($availableRule->passes('object_id', $booking->object_id));
    }
}
