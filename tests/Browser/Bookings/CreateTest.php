<?php

namespace Tests\Browser\Bookings;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/bookings')
                    ->clickLink('New booking')
                    ->assertPathIs('/bookings/create')
                    ->assertSourceHas('form');
        });
    }
}
