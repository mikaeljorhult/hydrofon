<?php

namespace Tests\Browser\Groups;

use Hydrofon\Group;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Groups can be updated through create form.
     *
     * @return void
     */
    public function testGroupsCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $group) {
            $browser->loginAs($admin)
                    ->visit('/groups/' . $group->id . '/edit')
                    ->type('name', 'New group Name')
                    ->press('Update')
                    ->assertPathIs('/groups')
                    ->assertSee('New group Name');
        });
    }

    /**
     * Groups must have a name.
     *
     * @return void
     */
    public function testGroupsMustHaveAName()
    {
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $group) {
            $browser->loginAs($admin)
                    ->visit('/groups/' . $group->id . '/edit')
                    ->type('name', '')
                    ->press('Update')
                    ->assertPathIs('/groups/' . $group->id . '/edit');
        });
    }
}
