<?php

namespace Tests\Browser\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GroupsEditTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testEditRouteIsReachable(): void
    {
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($group) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups')
                ->assertSeeLink($group->name)
                ->clickLink($group->name)
                ->assertPathIs('/groups/'.$group->id.'/edit')
                ->assertSee('Edit group')
                ->logout();
        });
    }

    public function testItemCanBeEdited(): void
    {
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($group) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups/'.$group->id.'/edit')
                ->assertSee('Edit group')
                ->type('name', 'New name')
                ->clickAndWaitForReload('@submitupdate')
                ->assertPathIs('/groups')
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Group::class, [
            'id' => $group->id,
            'name' => 'New name',
        ]);
    }

    public function testEditCanBeCancelled(): void
    {
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($group) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups/'.$group->id.'/edit')
                ->click('@submitcancel')
                ->assertPathIs('/groups')
                ->logout();
        });
    }
}
