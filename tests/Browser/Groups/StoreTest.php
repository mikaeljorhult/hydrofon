<?php

namespace Tests\Browser\Groups;

use Hydrofon\Group;
use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StoreTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Groups can be stored through create form.
     *
     * @return void
     */
    public function testGroupsCanBeStored()
    {
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->make();

        $this->browse(function (Browser $browser) use ($admin, $group) {
            $browser->loginAs($admin)
                    ->visit('/groups/create')
                    ->type('name', $group->name)
                    ->press('Create')
                    ->assertPathIs('/groups')
                    ->assertSee($group->name);
        });
    }

    /**
     * Requests with incomplete data is redirected back to create form.
     *
     * @return void
     */
    public function testInvalidGroupIsRedirectedBackToCreateForm()
    {
        $admin = factory(User::class)->states('admin')->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/groups/create')
                    ->press('Create')
                    ->assertPathIs('/groups/create');
        });
    }
}
