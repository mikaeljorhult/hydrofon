<?php

namespace Tests\Browser\Bookings;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class IndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to index page from home.
     *
     * @return void
     */
    public function testUserCanNavigateToIndexPage()
    {
        $user = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->clickLink('Bookings')
                    ->assertPathIs('/bookings');
        });
    }

    /**
     * Available categories are displayed on index page.
     *
     * @return void
     */
    public function testBookingIsListedOnIndexPage()
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $booking) {
            $browser->loginAs($user)
                    ->visit('/bookings')
                    ->assertSee($booking->start_time->format('Y-m-d H:i'));
        });
    }
}
