<?php

namespace Tests\Browser\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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
        $admin = User::factory()->admin()->create();

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
        $user = User::factory()->create();
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($user, $group) {
            $browser->loginAs($user)
                    ->visit('/groups')
                    ->assertSee($group->name);
        });
    }
}
