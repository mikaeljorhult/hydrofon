<?php

namespace Tests\Browser\Resources;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ResourcesIndexTest extends DuskTestCase
{
    use DatabaseTruncation;

    public function testIndexRouteIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/calendar')
                ->assertSeeLink('Resources')
                ->clickLink('Resources')
                ->assertPathIs('/resources')
                ->assertSee('Resources')
                ->logout();
        });
    }

    public function testNameIsDisplayedInListing(): void
    {
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->assertSee($resource->name)
                ->logout();
        });
    }

    public function testItemCanBeEditedInline(): void
    {
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->mouseover('@item-'.$resource->id)
                ->click('@inline-edit-'.$resource->id)
                ->waitFor('@inline-item-'.$resource->id)
                ->type('name', 'New name')
                ->pause(500)
                ->press('Save')
                ->waitFor('@item-'.$resource->id)
                ->assertSee('New name')
                ->logout();
        });

        $this->assertDatabaseHas(Resource::class, [
            'id' => $resource->id,
            'name' => 'New name',
        ]);
    }

    public function testInlineEditingCanBeCancelled(): void
    {
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->mouseover('@item-'.$resource->id)
                ->click('@inline-edit-'.$resource->id)
                ->waitFor('@inline-item-'.$resource->id)
                ->press('Cancel')
                ->waitFor('@item-'.$resource->id)
                ->assertSee($resource->name)
                ->logout();
        });
    }

    public function testItemCanBeDeleted(): void
    {
        $resource = Resource::factory()->create();

        $this->browse(function (Browser $browser) use ($resource) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->mouseover('@item-'.$resource->id)
                ->click('@delete-'.$resource->id)
                ->waitUntilMissing('@item-'.$resource->id)
                ->assertDontSee($resource->name)
                ->logout();
        });
    }

    public function testResourcessCreateIsReachable(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->assertSeeLink('New resource')
                ->clickLink('New resource')
                ->assertPathIs('/resources/create')
                ->assertSee('Create resource')
                ->logout();
        });
    }

    public function testMultipleItemsCanBeDeleted(): void
    {
        $resources = Resource::factory(5)->create();

        $this->browse(function (Browser $browser) use ($resources) {
            $browser
                ->loginAs(User::factory()->admin()->create())
                ->visit('/resources')
                ->check('[name="selected[]"][value="'.$resources->first()->id.'"]')
                ->check('[name="selected[]"][value="'.$resources->last()->id.'"]')
                ->click('@delete-multiple')
                ->waitUntilMissing('@item-'.$resources->first()->id)
                ->logout();
        });

        $this->assertDatabaseCount(Resource::class, 3);
        $this->assertModelMissing($resources->first());
        $this->assertModelMissing($resources->last());
    }
}
