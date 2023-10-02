<?php

namespace Tests\Browser\Groups;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class GroupsIndexTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testGroupsIndexIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/calendar')
                ->assertSeeLink('Groups')
                ->clickLink('Groups')
                ->assertPathIs('/groups')
                ->assertSee('Groups')
                ->logout();
        });
    }

    public function testNameIsDisplayedInListing(): void
    {
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($group) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups')
                ->assertSee($group->name)
                ->logout();
        });
    }

    public function testItemCanBeEditedInline(): void
    {
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($group) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups')
                ->mouseover('@item-'.$group->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$group->id)
                ->type('name', 'New name')
                ->pause(500)
                ->press('Save')
                ->waitFor('@item-'.$group->id)
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Group::class, [
            'id' => $group->id,
            'name' => 'New name',
        ]);
    }

    public function testInlineEditingCanBeCancelled(): void
    {
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($group) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups')
                ->mouseover('@item-'.$group->id)
                ->click('@inline-edit')
                ->waitFor('@inline-item-'.$group->id)
                ->press('Cancel')
                ->waitFor('@item-'.$group->id)
                ->assertSee($group->name)
                ->logout();
        });
    }

    public function testItemCanBeDeleted(): void
    {
        $group = Group::factory()->create();

        $this->browse(function (Browser $browser) use ($group) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/groups')
                ->mouseover('@item-'.$group->id)
                ->click('@delete')
                ->waitUntilMissing('@item-'.$group->id)
                ->assertDontSee($group->name)
                ->logout();
        });
    }

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
}
