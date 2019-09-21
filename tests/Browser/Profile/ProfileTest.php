<?php

namespace Tests\Browser\Profile;

use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProfileTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A user can navigate to the profile page.
     *
     * @return void
     */
    public function testUserCanVisitProfile()
    {
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->clickLink($user->name)
                    ->assertPathIs('/profile')
                    ->assertSee($user->name, 'main');
        });
    }
}
