<?php

namespace Tests\Feature\Resources;

use Hydrofon\User;
use Hydrofon\Group;
use Tests\TestCase;
use Hydrofon\Category;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $resource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('resources')
             ->assertSuccessful()
             ->assertSee(e($resource->name));
    }

    /**
     * Resources can be filtered by the name.
     *
     * @return void
     */
    public function testResourcesAreFilteredByName()
    {
        $visibleResource = factory(Resource::class)->create();
        $notVisibleResource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
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
        $visibleResource = factory(Resource::class)->create();
        $notVisibleResource = factory(Resource::class)->create();

        $visibleResource->categories()->attach(factory(Category::class)->create());

        $this->actingAs(factory(User::class)->states('admin')->create())
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
        $visibleResource = factory(Resource::class)->create();
        $notVisibleResource = factory(Resource::class)->create();

        $visibleResource->groups()->attach(factory(Group::class)->create());

        $this->actingAs(factory(User::class)->states('admin')->create())
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
        $visibleResource = factory(Resource::class)->states('facility')->create();
        $notVisibleResource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('resources?filter[is_facility]=1')
             ->assertSuccessful()
             ->assertSee(route('resources.edit', $visibleResource))
             ->assertDontSee(route('resources.edit', $notVisibleResource));
    }
}
