<?php

namespace Tests\Browser\Authentication;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LogoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A user can log out.
     *
     * @return void
     */
    public function testUserCanLogOut()
    {
        $user = factory(User::class)->create([
            'email'    => 'test@hydrofon.se',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->clickLink('Log out')
                    ->assertPathIs('/')
                    ->assertSeeLink('Log in');
        });
    }
}
