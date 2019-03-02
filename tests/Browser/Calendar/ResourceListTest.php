<?php

namespace Tests\Browser\Calendar;

use Hydrofon\Booking;
use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResourceListTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * The resource list is visible in the calendar view.
     *
     * @return void
     */
    public function testResourceListIsPresent()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->assertSourceHas('resourcelist');
        });
    }

    /**
     * Requested resources are shown in the calendar.
     *
     * @return void
     */
    public function testResourcesAreShownInCalendar()
    {
        $user     = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $this->browse(function (Browser $browser) use ($user, $resource) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->check('resources[]', $resource->id)
                    ->assertPathBeginsWith('/calendar')
                    ->waitFor('.segel-resource', 1)
                    ->assertSeeIn('.segel', $resource->name)
                    ->assertSourceMissing('segel-booking');
        });
    }

    /**
     * Bookings are shown in the calendar.
     *
     * @return void
     */
    public function testBookingsAreShownInCalendar()
    {
        $user    = factory(User::class)->create();
        $booking = factory(Booking::class)->create([
            'start_time' => now()->startOfDay()->hour(1),
            'end_time'   => now()->startOfDay()->hour(3),
        ]);

        $this->browse(function (Browser $browser) use ($user, $booking) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->check('resources[]', $booking->resource_id)
                    ->waitFor('.segel-resource', 1)
                    ->assertSeeIn('.segel', $booking->resource->name)
                    ->assertSourceHas('"segel-booking"');
        });
    }
}
