<?php

namespace Tests\Browser\Groups;

use App\Group;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class EditTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * User can navigate to edit page from groups index.
     *
     * @return void
     */
    public function testUserCanNavigateToEditPage()
    {
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();

        $this->browse(function (Browser $browser) use ($admin, $group) {
            $browser->loginAs($admin)
                    ->visit('/groups')
                    ->clickLink($group->name)
                    ->assertPathIs('/groups/'.$group->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
