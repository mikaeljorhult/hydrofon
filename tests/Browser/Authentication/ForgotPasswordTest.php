<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Notification;
use ProtoneMedia\LaravelDuskFakes\Notifications\PersistentNotifications;
use Tests\DuskTestCase;

class ForgotPasswordTest extends DuskTestCase
{
    use DatabaseMigrations, PersistentNotifications;

    public function testLoginPageLinkToPasswordReset(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSeeLink('Forgot password?')
                ->clickLink('Forgot password?')
                ->assertPathIs('/forgot-password');
        });
    }

    public function testUserCanRequestPasswordReset(): void
    {
        $user = User::factory()->create([
            'email' => 'test@hydrofon.se',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/forgot-password')
                ->assertSee('Password Reset')
                ->type('email', 'test@hydrofon.se')
                ->clickAndWaitForReload('[type=submit]')
                ->assertPathIs('/forgot-password')
                ->assertSee('We have emailed your password reset link.');
        });

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
