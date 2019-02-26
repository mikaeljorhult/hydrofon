<?php

namespace Tests\Feature\Desk;

use Hydrofon\Booking;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingsDeskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Current bookings are displayed on desk page.
     *
     * @return void
     */
    public function testCurrentBookingsAreDisplayed()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->states('current')->create(['user_id' => $user->id]);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk/'.$user->email);

        $response->assertStatus(200);
        $response->assertSee($booking->resource->name);
    }

    /**
     * Bookings older than four days are omitted.
     *
     * @return void
     */
    public function testOlderBookingsAreOmitted()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create([
            'user_id'    => $user->id,
            'start_time' => now()->subDays(5)->subHour(),
            'end_time'   => now()->subDays(5),
        ]);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk/'.$user->email);

        $response->assertStatus(200);
        $response->assertDontSee($booking->resource->name);
    }

    /**
     * Bookings more than four days in the future are omitted.
     *
     * @return void
     */
    public function testFutureBookingsAreOmitted()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create([
            'user_id'    => $user->id,
            'start_time' => now()->addDays(5),
            'end_time'   => now()->addDays(5)->addHour(),
        ]);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk/'.$user->email);

        $response->assertStatus(200);
        $response->assertDontSee($booking->resource->name);
    }

    /**
     * Bookings that have been checked in are omitted.
     *
     * @return void
     */
    public function testCheckedInBookingsAreOmitted()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create(['user_id' => $user->id]);
        $booking->checkin()->create(['user_id' => $user->id]);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk/'.$user->email);

        $response->assertStatus(200);
        $response->assertDontSee($booking->resource->name);
    }

    /**
     * Bookings older than four days are omitted.
     *
     * @return void
     */
    public function testBookingsTimeSpanCanBeFiltered()
    {
        $user = factory(User::class)->create();
        $booking = factory(Booking::class)->create([
            'user_id'    => $user->id,
            'start_time' => now()->subDays(5)->subHour(),
            'end_time'   => now()->subDays(5),
        ]);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->get('/desk/'.$user->email.'?filter[between]='.now()->subDays(6)->format('U').','.now()->subDays(4)->format('U'));

        $response->assertStatus(200);
        $response->assertSee($booking->resource->name);
    }
}
