<?php

namespace Tests\Feature\Bookings;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Bookings are listed in index.
     */
    public function testBookingsAreListed(): void
    {
        $booking = Booking::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('bookings')
            ->assertSuccessful()
            ->assertSee($booking->resource->name);
    }

    /**
     * Bookings can be filtered by the resource.
     */
    public function testBookingsAreFilteredByResource(): void
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
     */
    public function testBookingsAreFilteredByUser(): void
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
     */
    public function testBookingsAreFilteredByStartTime(): void
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
     */
    public function testBookingsAreFilteredByEndTime(): void
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
