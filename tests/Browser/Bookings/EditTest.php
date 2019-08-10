<?php

namespace Tests\Browser\Bookings;

use Hydrofon\User;
use Hydrofon\Booking;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from bookings index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $user = factory(User::class)->states('admin')->create();
        $booking = factory(Booking::class)->create();

        $this->browse(function (Browser $browser) use ($user, $booking) {
            $browser->loginAs($user)
                    ->visit('/bookings')
                    ->clickLink($booking->resource->name)
                    ->assertPathIs('/bookings/'.$booking->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
