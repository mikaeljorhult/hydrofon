<?php

namespace Tests\Unit\Model;

use Hydrofon\Booking;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Booking has an owner.
     *
     * @return void
     */
    public function testBookingHasAnOwner()
    {
        $booking = factory(Booking::class)->create();

        $this->assertInstanceOf(User::class, $booking->user);
    }

    /**
     * Booking has a creator.
     *
     * @return void
     */
    public function testBookingHasACreator()
    {
        $booking = factory(Booking::class)->create();

        $this->assertInstanceOf(User::class, $booking->created_by);
    }

    /**
     * Booking has an object.
     *
     * @return void
     */
    public function testBookingHasAnObject()
    {
        $booking = factory(Booking::class)->create();

        $this->assertInstanceOf(Object::class, $booking->object);
    }

    /**
     * Bookings that end outside date range should be exluded in between scope.
     *
     * @return void
     */
    public function testBetweenScopeExcludeBookings()
    {
        // Create a past booking.
        factory(Booking::class)->states('past')->create();

        // Create a booking between 11.00 and 12.00 on current date.
        $booking = factory(Booking::class)->create([
            'start_time' => today()->hour(11),
            'end_time'   => today()->hour(12),
        ]);

        // Get all bookings of current date (00.00 - 24.00).
        $bookings = Booking::between(today(), today()->addDay())->get();

        // Only current date booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }

    /**
     * Bookings that end on start time of between scope should be excluded.
     *
     * @return void
     */
    public function testBetweenScopeExcludeBookingsThatEndOnStartTime()
    {
        // Create a booking between 12.00 yesterday and 00.00 on current date.
        factory(Booking::class)->create([
            'start_time' => today()->subDay()->hour(12),
            'end_time'   => today()->hour(0),
        ]);

        // Get all bookings of current date.
        $bookings = Booking::between(today(), today()->addDay())->get();

        // No bookings should be returned.
        $this->assertCount(0, $bookings);
    }

    /**
     * Bookings that start on start time of between scope should be included.
     *
     * @return void
     */
    public function testBetweenScopeIncludeBookingsThatStartOnStartTime()
    {
        // Create a past booking.
        factory(Booking::class)->states('past')->create();

        // Create a booking between 00.00 and 04.00 on current date.
        $booking = factory(Booking::class)->create([
            'start_time' => today()->hour(0),
            'end_time'   => today()->hour(4),
        ]);

        // Get all bookings of current date.
        $bookings = Booking::between(today(), today()->addDay())->get();

        // Only current date booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }

    /**
     * Bookings that end on end time of between scope should be included.
     *
     * @return void
     */
    public function testBetweenScopeIncludeBookingsThatEndOnEndTime()
    {
        // Create a past booking.
        factory(Booking::class)->states('past')->create();

        // Create a booking between 00.00 and 04.00 on current date.
        $booking = factory(Booking::class)->create([
            'start_time' => today()->hour(0),
            'end_time'   => today()->addDay()->hour(0),
        ]);

        // Get all bookings of current date.
        $bookings = Booking::between(today(), today()->addDay())->get();

        // Only current date booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }

    /**
     * Bookings that start and end outside between scope should be included.
     *
     * @return void
     */
    public function testBetweenScopeIncludeBookingsThatStartAndEndOutsideScope()
    {
        // Create a booking between 00.00 and 04.00 on current date.
        $booking = factory(Booking::class)->create([
            'start_time' => now()->subYear(),
            'end_time'   => now()->addYear(),
        ]);

        // Get all bookings of current date.
        $bookings = Booking::between(today(), today()->addDay())->get();

        // Only current date booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }

    /**
     * Bookings that end before current time should be included.
     *
     * @return void
     */
    public function testPastScopeIncludePastBookings()
    {
        // Create a current and a future booking.
        factory(Booking::class)->states('current')->create();
        factory(Booking::class)->states('future')->create();

        // Create a past booking.
        $booking = factory(Booking::class)->states('past')->create();

        // Get all past bookings.
        $bookings = Booking::past()->get();

        // Only past booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }

    /**
     * Bookings that end before current time should be included.
     *
     * @return void
     */
    public function testFutureScopeIncludeFutureBookings()
    {
        // Create a current and a past booking.
        factory(Booking::class)->states('current')->create();
        factory(Booking::class)->states('past')->create();

        // Create a future booking.
        $booking = factory(Booking::class)->states('future')->create();

        // Get all future bookings.
        $bookings = Booking::future()->get();

        // Only future booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }

    /**
     * Bookings that has started but not ended should be included.
     *
     * @return void
     */
    public function testCurrentScopeIncludeCurrentBookings()
    {
        // Create a past and a future booking.
        factory(Booking::class)->states('past')->create();
        factory(Booking::class)->states('future')->create();

        // Create a current booking.
        $booking = factory(Booking::class)->states('current')->create();

        // Get all current bookings.
        $bookings = Booking::current()->get();

        // Only future booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }
}
