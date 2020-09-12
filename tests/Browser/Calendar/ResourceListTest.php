<?php

namespace Tests\Browser\Calendar;

use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
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
        $user = User::factory()->create();

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
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

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
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
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
