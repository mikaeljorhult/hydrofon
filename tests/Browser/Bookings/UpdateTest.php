<?php

namespace Tests\Browser\Bookings;

use App\Booking;
use App\Resource;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Bookings can be updated through create form.
     *
     * @return void
     */
    public function testBookingsCanBeUpdated()
    {
        $booking = Booking::factory()->create();
        $newResource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($booking, $newResource) {
            $browser->loginAs($booking->user)
                    ->visit('/bookings/'.$booking->id.'/edit')
                    ->type('resource_id', $newResource->id)
                    ->press('Update')
                    ->assertPathIs('/bookings')
                    ->assertSee($newResource->name);
        });
    }

    /**
     * Bookings must have an resource.
     *
     * @return void
     */
    public function testBookingsMustHaveAResource()
    {
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser->loginAs($booking->user)
                    ->visit('/bookings/'.$booking->id.'/edit')
                    ->type('resource_id', '')
                    ->press('Update')
                    ->assertPathIs('/bookings/'.$booking->id.'/edit');
        });
    }
}
