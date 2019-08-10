<?php

namespace Tests\Browser\Bookings;

use Hydrofon\Booking;
use Hydrofon\Resource;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
        $booking = factory(Booking::class)->create();
        $newResource = factory(Resource::class)->create();

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
        $booking = factory(Booking::class)->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser->loginAs($booking->user)
                    ->visit('/bookings/'.$booking->id.'/edit')
                    ->type('resource_id', '')
                    ->press('Update')
                    ->assertPathIs('/bookings/'.$booking->id.'/edit');
        });
    }
}
