<?php

namespace Tests\Browser\Groups;

use App\Models\Group;
use App\Models\User;
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
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($admin, $group) {
            $browser->loginAs($admin)
                    ->visit('/groups')
                    ->clickLink($group->name)
                    ->assertPathIs('/groups/'.$group->id.'/edit')
                    ->assertSourceHas('form');
        });
    }
}
