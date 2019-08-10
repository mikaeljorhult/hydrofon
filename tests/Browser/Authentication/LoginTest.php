<?php

namespace Tests\Browser\Authentication;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A user can log in.
     *
     * @return void
     */
    public function testUserCanLogIn()
    {
        $user = factory(User::class)->create([
            'email'    => 'test@hydrofon.se',
            'password' => bcrypt('password'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Log in')
                    ->assertPathIs('/')
                    ->assertSeeLink('Log out')
                    ->assertAuthenticatedAs($user);
        });
    }
}
