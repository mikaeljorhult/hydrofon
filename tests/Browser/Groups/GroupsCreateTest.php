<?php

namespace Tests\Browser\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GroupsCreateTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testGroupsCreateIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups')
                ->assertSeeLink('New group')
                ->clickLink('New group')
                ->assertPathIs('/groups/create')
                ->assertSee('Create group')
                ->logout();
        });
    }

    public function testItemCanBeCreated(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups/create')
                ->assertSee('Create group')
                ->type('name', 'New group')
                ->clickAndWaitForReload('@submitcreate')
                ->assertPathIs('/groups')
                ->logout();
        });

        $this->assertDatabaseHas(Group::class, [
            'name' => 'New group',
        ]);
    }

    public function testCreateCanBeCancelled(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups/create')
                ->click('@submitcancel')
                ->assertPathIs('/groups')
                ->logout();
        });
    }
}
