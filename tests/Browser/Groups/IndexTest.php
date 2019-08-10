<?php

namespace Tests\Browser\Groups;

use Hydrofon\User;
use Hydrofon\Group;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class IndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to index page from home.
     *
     * @return void
     */
    public function testUserCanNavigateToIndexPage()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/calendar')
                    ->clickLink('Groups')
                    ->assertPathIs('/groups');
        });
    }

    /**
     * Available groups are displayed on index page.
     *
     * @return void
     */
    public function testResourceIsListedOnIndexPage()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $this->browse(function (Browser $browser) use ($user, $group) {
            $browser->loginAs($user)
                    ->visit('/groups')
                    ->assertSee($group->name);
        });
    }
}
