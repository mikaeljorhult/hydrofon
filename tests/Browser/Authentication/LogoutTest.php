<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $user = User::factory()->create([
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
