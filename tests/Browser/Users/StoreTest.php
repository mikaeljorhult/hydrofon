<?php

namespace Tests\Browser\Users;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->make();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->make();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/users/create')
                    ->press('Create')
                    ->assertPathIs('/users/create');
        });
    }
}
