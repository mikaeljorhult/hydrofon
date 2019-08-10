<?php

namespace Tests\Browser\Bookings;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to create page from bookings index.
     *
     * @return void
     */
    public function testUserCanNavigateToCreatePage()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/bookings')
                    ->clickLink('New booking')
                    ->assertPathIs('/bookings/create')
                    ->assertSourceHas('form');
        });
    }
}
