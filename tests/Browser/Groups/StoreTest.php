<?php

namespace Tests\Browser\Groups;

use App\Models\Group;
use App\Models\User;
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
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->make();

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
        $admin = User::factory()->admin()->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                    ->visit('/groups/create')
                    ->press('Create')
                    ->assertPathIs('/groups/create');
        });
    }
}
