<?php

namespace Tests\Browser\Bookings;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $user = User::factory()->admin()->create();
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $booking) {
            $browser->loginAs($user)
                    ->visit('/bookings')
                    ->clickLink($booking->resource->name)
                    ->assertPathIs('/bookings/'.$booking->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
