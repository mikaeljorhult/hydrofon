<?php

namespace Tests\Unit;

use Hydrofon\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

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
}
