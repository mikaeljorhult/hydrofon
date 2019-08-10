<?php

namespace Tests\Browser\Users;

use Hydrofon\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from users index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $user) {
            $browser->loginAs($admin)
                    ->visit('/users')
                    ->clickLink($user->email)
                    ->assertPathIs('/users/'.$user->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
