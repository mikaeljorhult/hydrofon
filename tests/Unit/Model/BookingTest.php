<?php

namespace Tests\Unit\Model;

use App\Booking;
use App\Checkin;
use App\Checkout;
use App\Resource;
use App\User;
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
        $booking = Booking::factory()->create();

        $this->assertInstanceOf(User::class, $booking->user);
    }

    /**
     * Booking has a creator.
     *
     * @return void
     */
    public function testBookingHasACreator()
    {
        $this->actingAs($user = User::factory()->create());
        $booking = Booking::factory()->create();

        $this->assertInstanceOf(User::class, $booking->created_by);
        $this->assertEquals($user->id, $booking->created_by->id);
    }

    /**
     * Booking has an resource.
     *
     * @return void
     */
    public function testBookingHasAResource()
    {
        $this->actingAs(User::factory()->admin()->create());

        $booking = Booking::factory()->create();

        $this->assertInstanceOf(Resource::class, $booking->resource);
    }

    /**
     * Bookings that end outside date range should be excluded in between scope.
     *
     * @return void
     */
    public function testBetweenScopeExcludeBookings()
    {
        // Create a past booking.
        Booking::factory()->past()->create();

        // Create a booking between 11.00 and 12.00 on current date.
        $booking = Booking::factory()->create([
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
        Booking::factory()->create([
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
        Booking::factory()->past()->create();

        // Create a booking between 00.00 and 04.00 on current date.
        $booking = Booking::factory()->create([
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
        Booking::factory()->past()->create();

        // Create a booking between 00.00 and 04.00 on current date.
        $booking = Booking::factory()->create([
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
        $booking = Booking::factory()->create([
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
        Booking::factory()->current()->create();
        Booking::factory()->future()->create();

        // Create a past booking.
        $booking = Booking::factory()->past()->create();

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
        Booking::factory()->current()->create();
        Booking::factory()->past()->create();

        // Create a future booking.
        $booking = Booking::factory()->future()->create();

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
        Booking::factory()->past()->create();
        Booking::factory()->future()->create();

        // Create a current booking.
        $booking = Booking::factory()->current()->create();

        // Get all current bookings.
        $bookings = Booking::current()->get();

        // Only future booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }

    /**
     * Past bookings that has been checked out but not yet checked back in should be included.
     *
     * @return void
     */
    public function testOverdueScopeIncludeOverdueBookings()
    {
        // Create a past and a future booking.
        Booking::factory()->past()->create();
        Booking::factory()->future()->create();
        $checkedInBooking = Booking::factory()->past()->create();
        $checkedInBooking->checkout()->save(Checkout::factory()->create());
        $checkedInBooking->checkin()->save(Checkin::factory()->create());

        // Create a current booking.
        $booking = Booking::factory()->past()->create();
        $booking->checkout()->save(Checkout::factory()->create());

        // Get all current bookings.
        $bookings = Booking::overdue()->get();

        // Only future booking should be returned.
        $this->assertCount(1, $bookings);
        $this->assertEquals($bookings->first()->id, $booking->id);
    }
}
