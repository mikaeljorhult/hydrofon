<?php

namespace Tests\Browser\Calendar;

use App\Models\User;
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
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/calendar')
                    ->assertSourceHas('segel');
        });
    }
}
