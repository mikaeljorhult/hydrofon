<?php

namespace Tests\Feature\Bookings;

use App\Booking;
use App\User;
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
        $this->withoutExceptionHandling();
        $booking = Booking::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
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
        $visibleBooking = Booking::factory()->create();
        $notVisibleBooking = Booking::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
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
        $visibleBooking = Booking::factory()->create();
        $notVisibleBooking = Booking::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
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
        $visibleBooking = Booking::factory()->future()->create();
        $notVisibleBooking = Booking::factory()->past()->create();

        $this->actingAs(User::factory()->admin()->create())
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
        $visibleBooking = Booking::factory()->future()->create();
        $notVisibleBooking = Booking::factory()->past()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('bookings?filter[end_time]='.$visibleBooking->start_time->format('Y-m-d'))
             ->assertSuccessful()
             ->assertSee(route('bookings.edit', $visibleBooking))
             ->assertDontSee(route('bookings.edit', $notVisibleBooking));
    }
}
