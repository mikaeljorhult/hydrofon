<?php

namespace Tests\Browser\Users;

use App\User;
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

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/users/create')
                    ->press('Create')
                    ->assertPathIs('/users/create');
        });
    }
}
