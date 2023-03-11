<?php

namespace Tests\Feature\Desk;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class BookingsDeskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Current bookings are displayed on desk page.
     */
    public function testCurrentBookingsAreDisplayed(): void
    {
        $booking = Booking::factory()->current()->create();

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/'.$booking->user->email);

        $response->assertStatus(200);
        $response->assertSee($booking->resource->name);
    }

    /**
     * Older bookings are omitted.
     */
    public function testOlderBookingsAreOmitted(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->subHours(2),
            'end_time' => now()->subHour(),
        ]);

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/'.$booking->user->email);

        $response->assertStatus(200);
        $response->assertDontSee($booking->resource->name);
    }

    /**
     * Future bookings are omitted.
     */
    public function testFutureBookingsAreOmitted(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
        ]);

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/'.$booking->user->email);

        $response->assertStatus(200);
        $response->assertDontSee($booking->resource->name);
    }

    /**
     * Bookings that have been checked in are omitted.
     */
    public function testCheckedInBookingsAreOmitted(): void
    {
        $booking = Booking::factory()->checkedin()->createQuietly();

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/'.$booking->user->email);

        $response->assertStatus(200);
        $response->assertDontSee($booking->resource->name);
    }

    /**
     * Time span of desk can be changed through filters.
     */
    public function testBookingsTimeSpanCanBeFiltered(): void
    {
        $booking = Booking::factory()->create([
            'start_time' => now()->subDays(5)->subHour(),
            'end_time' => now()->subDays(5),
        ]);

        $response = $this->actingAs(User::factory()->admin()->create())
                         ->get('/desk/'.$booking->user->email.'?filter[between]='.now()->subDays(6)->format('U').','.now()->subDays(4)->format('U'));

        $response->assertStatus(200);
        $response->assertSee($booking->resource->name);
    }

    /**
     * Time span of desk can be changed through configuration.
     */
    public function testBookingsTimeSpanCanBeConfigured(): void
    {
        Config::set('hydrofon.desk_inclusion_hours.earlier', 1);
        Config::set('hydrofon.desk_inclusion_hours.later', 1);

        $user = User::factory()->create();

        $earlierBooking = Booking::factory()->for($user)->create([
            'start_time' => now()->subHour(),
            'end_time' => now()->subMinutes(30),
        ]);

        $laterBooking = Booking::factory()->for($user)->create([
            'start_time' => now()->addMinutes(30),
            'end_time' => now()->addHour(),
        ]);

        $response = $this->actingAs(User::factory()->admin()->create())->get('/desk/'.$user->email);

        $response->assertStatus(200);
        $response->assertSee($earlierBooking->resource->name);
        $response->assertSee($laterBooking->resource->name);
    }
}
