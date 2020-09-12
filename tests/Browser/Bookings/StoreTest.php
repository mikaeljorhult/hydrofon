<?php

namespace Tests\Browser\Bookings;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StoreTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Bookings can be stored through create form.
     *
     * @return void
     */
    public function testBookingsCanBeStored()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->make();

        $this->browse(function (Browser $browser) use ($user, $booking) {
            $browser->loginAs($user)
                    ->visit('/bookings/create')
                    ->type('resource_id', $booking->resource_id)
                    ->type('start_time', $booking->start_time->format('Y-m-d H:i:s'))
                    ->type('end_time', $booking->end_time->format('Y-m-d H:i:s'))
                    ->press('Create')
                    ->assertPathIs('/bookings')
                    ->assertSee($booking->resource->name);
        });
    }

    /**
     * Requests with incomplete data is redirected back to create form.
     *
     * @return void
     */
    public function testInvalidBookingIsRedirectedBackToCreateForm()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->make();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/bookings/create')
                    ->press('Create')
                    ->assertPathIs('/bookings/create');
        });
    }
}
