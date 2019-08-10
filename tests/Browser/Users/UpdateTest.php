<?php

namespace Tests\Browser\Users;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Users can be updated through create form.
     *
     * @return void
     */
    public function testUsersCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/users/'.$user->id.'/edit')
                    ->type('name', 'New user name')
                    ->type('email', $user->email)
                    ->press('Update')
                    ->assertPathIs('/users')
                    ->assertSee('New user name');
        });
    }

    /**
     * Users must have a name.
     *
     * @return void
     */
    public function testUsersMustHaveAName()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/users/'.$user->id.'/edit')
                    ->type('name', '')
                    ->type('email', $user->email)
                    ->press('Update')
                    ->assertPathIs('/users/'.$user->id.'/edit');
        });
    }
}
