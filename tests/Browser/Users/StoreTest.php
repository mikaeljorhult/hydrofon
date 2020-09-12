<?php

namespace Tests\Browser\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StoreTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Users can be stored through create form.
     *
     * @return void
     */
    public function testUsersCanBeStored()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->make();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/users/create')
                    ->type('name', $user->name)
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->type('password_confirmation', 'password')
                    ->press('Create')
                    ->assertPathIs('/users')
                    ->assertSee($user->email);
        });
    }

    /**
     * Requests with incomplete data is redirected back to create form.
     *
     * @return void
     */
    public function testInvalidUserIsRedirectedBackToCreateForm()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->make();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/users/create')
                    ->press('Create')
                    ->assertPathIs('/users/create');
        });
    }
}
