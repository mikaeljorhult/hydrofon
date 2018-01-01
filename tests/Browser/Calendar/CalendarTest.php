<?php

namespace Tests\Browser\Calendar;

use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CalendarTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A user can see the calendar page.
     *
     * @return void
     */
    public function testUserCanVisitCalendar()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->assertSourceHas('segel');
        });
    }

    /**
     * A user can go to previous date through link.
     *
     * @return void
     */
    public function testPreviousLinkGoesToYesterday()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->clickLink('Previous')
                    ->assertPathIs('/calendar/' . now()->subDay()->format('Y-m-d'));
        });
    }

    /**
     * A user can go to next date through link.
     *
     * @return void
     */
    public function testNextLinkGoesToTomorrow()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->clickLink('Next')
                    ->assertPathIs('/calendar/' . now()->addDay()->format('Y-m-d'));
        });
    }
}
