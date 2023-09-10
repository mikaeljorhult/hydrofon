<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ForgotPasswordTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testLoginPageLinkToPasswordReset(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSeeLink('Forgot password?')
                ->clickLink('Forgot password?')
                ->assertPathIs('/password/reset');
        });
    }

    public function testUserCanRequestPasswordReset(): void
    {
        User::factory()->create([
            'email' => 'test@hydrofon.se',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/password/reset')
                ->assertSee('Password Reset')
                ->type('email', 'test@hydrofon.se')
                ->clickAndWaitForReload('[type=submit]')
                ->assertPathIs('/password/email');
        });
    }
}
