<?php

namespace Tests\Browser\Calendar;

use Hydrofon\Booking;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ObjectListTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * The object list is visible in the calendar view.
     *
     * @return void
     */
    public function testObjectListIsPresent()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->assertSourceHas('objectlist');
        });
    }

    /**
     * User can select date to be displayed in calendar.
     *
     * @return void
     */
    public function testDateCanBeSelected()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->type('date', '2017-01-01')
                    ->press('Show calendar')
                    ->assertPathIs('/calendar/2017-01-01');
        });
    }

    /**
     * Requested objects are shown in the calendar.
     *
     * @return void
     */
    public function testObjectsAreShownInCalendar()
    {
        $user   = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $this->browse(function (Browser $browser) use ($user, $object) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->check('objects[]', $object->id)
                    ->press('Show calendar')
                    ->assertPathBeginsWith('/calendar/')
                    ->assertSeeIn('.segel', $object->name)
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
                    ->check('objects[]', $booking->object_id)
                    ->press('Show calendar')
                    ->assertSeeIn('.segel', $booking->object->name)
                    ->assertSourceHas('"segel-booking"');
        });
    }
}
