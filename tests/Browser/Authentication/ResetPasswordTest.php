<?php

namespace Tests\Browser\Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResetPasswordTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function testUserCanRequestPasswordReset(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);

        $this->browse(function (Browser $browser) use ($user, $token) {
            $browser->visit('reset-password/'.$token)
                ->assertSee('Password Reset')
                ->type('email', $user->email)
                ->type('password', 'new-password')
                ->type('password_confirmation', 'new-password')
                ->clickAndWaitForReload('[type=submit]')
                ->assertPathIs('/login');
        });

        $user->refresh();
        $this->assertTrue(Hash::check('new-password', $user->password));
    }
}
