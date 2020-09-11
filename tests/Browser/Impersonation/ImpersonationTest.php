<?php

namespace Tests\Browser\Impersonation;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ImpersonationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * An administrator can impersonate a user.
     *
     * @return void
     */
    public function testAdminCanImpersonateUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/calendar')
                    // See impersonation form.
                    ->assertSourceHas('topbar-impersonation')
                    ->select('user_id', $user->id)
                    ->keys('[name="user_id"]', '{enter}')

                    // User is impersonated.
                    ->assertPathIs('/calendar')
                    ->assertDontSeeIn('.sidebar', $admin->name)
                    ->assertSeeIn('.sidebar', $user->name)
                    ->assertSeeLink('Stop impersonation')

                    // Stop impersonating, recoqnized as administrator user again.
                    ->clickLink('Stop impersonation')
                    ->assertPathIs('/calendar')
                    ->assertDontSeeIn('.sidebar', $user->name)
                    ->assertSeeIn('.sidebar', $admin->name)
                    ->assertDontSeeLink('Stop impersonation');
        });
    }

    /**
     * A user can not see impersonation form.
     *
     * @return void
     */
    public function testUserCanNotSeeImpersonationForm()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user1) {
            $browser->loginAs($user1)
                    ->visit('/calendar')
                    ->assertSourceMissing('topbar-impersonation');
        });
    }
}
