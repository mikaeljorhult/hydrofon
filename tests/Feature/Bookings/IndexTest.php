<?php

namespace Tests\Feature\Bookings;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings are listed in index.
     *
     * @return void
     */
    public function testBookingsAreListed()
    {
        $booking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings')
             ->assertSuccessful()
             ->assertSee($booking->resource->name);
    }

    /**
     * Bookings can be filtered by the resource.
     *
     * @return void
     */
    public function testBookingsAreFilteredByResource()
    {
        $visibleBooking = factory(Booking::class)->create();
        $notVisibleBooking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?filter[resource_id]='.$visibleBooking->resource->id)
             ->assertSuccessful()
             ->assertSee(route('bookings.edit', $visibleBooking))
             ->assertDontSee(route('bookings.edit', $notVisibleBooking));
    }

    /**
     * Bookings can be filtered by the user.
     *
     * @return void
     */
    public function testBookingsAreFilteredByUser()
    {
        $visibleBooking = factory(Booking::class)->create();
        $notVisibleBooking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?filter[user_id]='.$visibleBooking->user->id)
             ->assertSuccessful()
             ->assertSee(route('bookings.edit', $visibleBooking))
             ->assertDontSee(route('bookings.edit', $notVisibleBooking));
    }

    /**
     * Bookings can be filtered by the start time.
     *
     * @return void
     */
    public function testBookingsAreFilteredByStartTime()
    {
        $visibleBooking = factory(Booking::class)->states('future')->create();
        $notVisibleBooking = factory(Booking::class)->states('past')->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?filter[start_time]='.$visibleBooking->start_time->format('Y-m-d'))
             ->assertSuccessful()
             ->assertSee(route('bookings.edit', $visibleBooking))
             ->assertDontSee(route('bookings.edit', $notVisibleBooking));
    }

    /**
     * Bookings can be filtered by the end time.
     *
     * @return void
     */
    public function testBookingsAreFilteredByEndTime()
    {
        $visibleBooking = factory(Booking::class)->states('future')->create();
        $notVisibleBooking = factory(Booking::class)->states('past')->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?filter[end_time]='.$visibleBooking->start_time->format('Y-m-d'))
             ->assertSuccessful()
             ->assertSee(route('bookings.edit', $visibleBooking))
             ->assertDontSee(route('bookings.edit', $notVisibleBooking));
    }
}
