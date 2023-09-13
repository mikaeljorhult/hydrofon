<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testLoginPageIsAvailable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Log in');
        });
    }

    public function testUserCanLogIn(): void
    {
        User::factory()->create([
            'email' => 'test@hydrofon.se',
            'password' => \Hash::make('password'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@hydrofon.se')
                ->type('password', 'password')
                ->clickAndWaitForReload('[type=submit]')
                ->assertAuthenticated()
                ->assertPathIs('/calendar');
        });
    }
}
