<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testRegisterPageIsAvailable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Register');
        });
    }

    public function testGuestCanRegister(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'Test User')
                ->type('email', 'test@hydrofon.se')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->clickAndWaitForReload('[type=submit]')
                ->assertAuthenticated();
        });

        $this->assertDatabaseHas(User::class, [
            'name' => 'Test User',
            'email' => 'test@hydrofon.se',
        ]);
    }
}
