<?php

namespace Tests\Browser\Bookings;

use Hydrofon\Booking;
use Hydrofon\Object;
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
        $booking   = factory(Booking::class)->create();
        $newObject = factory(Object::class)->create();

        $this->browse(function (Browser $browser) use ($booking, $newObject) {
            $browser->loginAs($booking->user)
                    ->visit('/bookings/' . $booking->id . '/edit')
                    ->type('object_id', $newObject->id)
                    ->press('Update')
                    ->assertPathIs('/bookings')
                    ->assertSee($newObject->name);
        });
    }

    /**
     * Bookings must have an object.
     *
     * @return void
     */
    public function testBookingsMustHaveAnObject()
    {
        $booking = factory(Booking::class)->create();

        $this->browse(function (Browser $browser) use ($booking) {
            $browser->loginAs($booking->user)
                    ->visit('/bookings/' . $booking->id . '/edit')
                    ->type('object_id', '')
                    ->press('Update')
                    ->assertPathIs('/bookings/' . $booking->id . '/edit');
        });
    }
}
