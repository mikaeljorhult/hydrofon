<?php

namespace Tests\Feature\Resources;

use App\Category;
use App\Group;
use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources are listed in index.
     *
     * @return void
     */
    public function testResourcesAreListed()
    {
        $resource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('resources')
             ->assertSuccessful()
             ->assertSee($resource->name);
    }

    /**
     * Resources can be filtered by the name.
     *
     * @return void
     */
    public function testResourcesAreFilteredByName()
    {
        $visibleResource = Resource::factory()->create();
        $notVisibleResource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('resources?filter[name]='.$visibleResource->name)
             ->assertSuccessful()
             ->assertSee(route('resources.edit', $visibleResource))
             ->assertDontSee(route('resources.edit', $notVisibleResource));
    }

    /**
     * Resources can be filtered by category.
     *
     * @return void
     */
    public function testResourcesAreFilteredByCategory()
    {
        $visibleResource = Resource::factory()->create();
        $notVisibleResource = Resource::factory()->create();

        $visibleResource->categories()->attach(Category::factory()->create());

        $this->actingAs(User::factory()->admin()->create())
             ->get('resources?filter[categories.id]='.$visibleResource->categories->first()->id)
             ->assertSuccessful()
             ->assertSee(route('resources.edit', $visibleResource))
             ->assertDontSee(route('resources.edit', $notVisibleResource));
    }

    /**
     * Resources can be filtered by group.
     *
     * @return void
     */
    public function testResourcesAreFilteredByGroup()
    {
        $visibleResource = Resource::factory()->create();
        $notVisibleResource = Resource::factory()->create();

        $visibleResource->groups()->attach(Group::factory()->create());

        $this->actingAs(User::factory()->admin()->create())
             ->get('resources?filter[groups.id]='.$visibleResource->groups->first()->id)
             ->assertSuccessful()
             ->assertSee(route('resources.edit', $visibleResource))
             ->assertDontSee(route('resources.edit', $notVisibleResource));
    }

    /**
     * Resources can be filtered by facility status.
     *
     * @return void
     */
    public function testResourcesAreFilteredByFacility()
    {
        $visibleResource = Resource::factory()->facility()->create();
        $notVisibleResource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('resources?filter[is_facility]=1')
             ->assertSuccessful()
             ->assertSee(route('resources.edit', $visibleResource))
             ->assertDontSee(route('resources.edit', $notVisibleResource));
    }
}
