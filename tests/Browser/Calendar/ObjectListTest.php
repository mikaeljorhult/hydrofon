<?php

namespace Tests\Browser\Calendar;

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
     * A user can see the calendar page.
     *
     * @return void
     */
    public function testUserCanVisitCalendar()
    {
        $user   = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $this->browse(function (Browser $browser) use ($user, $object) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->check('objects[]', $object->id)
                    ->press('Show calendar')
                    ->assertPathBeginsWith('/calendar/')
                    ->assertSeeIn('.segel', $object->name);
        });
    }
}
