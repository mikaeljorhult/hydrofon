<?php

namespace Tests\Feature\Bookings;

use Hydrofon\Booking;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
     * Bookings index can be filtered by resource name.
     *
     * @return void
     */
    public function testBookingsCanBeFilteredByResourceName()
    {
        $visibleBooking = factory(Booking::class)->create();
        $notVisibleBooking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?'.http_build_query([
                     'filter' => [
                         'query' => $visibleBooking->resource->name,
                     ],
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleBooking->resource->name)
             ->assertDontSee($notVisibleBooking->resource->name);
    }

    /**
     * Bookings index can be filtered by user name.
     *
     * @return void
     */
    public function testBookingsCanBeFilteredByUserName()
    {
        $visibleBooking = factory(Booking::class)->create();
        $notVisibleBooking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?'.http_build_query([
                     'filter' => [
                         'query' => $visibleBooking->user->name,
                     ],
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleBooking->resource->name)
             ->assertDontSee($notVisibleBooking->resource->name);
    }

    /**
     * Bookings index can be filtered by a user.
     *
     * @return void
     */
    public function testBookingsCanBeFilteredByUser()
    {
        $visibleBooking = factory(Booking::class)->create();
        $notVisibleBooking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?'.http_build_query([
                     'filter' => [
                         'user' => $visibleBooking->user->id,
                     ],
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleBooking->resource->name)
             ->assertDontSee($notVisibleBooking->resource->name);
    }

    /**
     * Bookings index can be filtered by a resource.
     *
     * @return void
     */
    public function testBookingsCanBeFilteredByResource()
    {
        $visibleBooking = factory(Booking::class)->create();
        $notVisibleBooking = factory(Booking::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('bookings?'.http_build_query([
                     'filter' => [
                         'user' => $visibleBooking->resource->id,
                     ],
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleBooking->resource->name)
             ->assertDontSee($notVisibleBooking->resource->name);
    }
}
