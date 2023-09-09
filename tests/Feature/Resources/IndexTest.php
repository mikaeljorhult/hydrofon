<?php

namespace Tests\Feature\Resources;

use App\Models\Category;
use App\Models\Group;
use App\Models\Resource;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources are listed in index.
     */
    public function testResourcesAreListed(): void
    {
        $resource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('resources')
            ->assertSuccessful()
            ->assertSee($resource->name);
    }

    /**
     * Resources can be filtered by the name.
     */
    public function testResourcesAreFilteredByName(): void
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
     */
    public function testResourcesAreFilteredByCategory(): void
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
     */
    public function testResourcesAreFilteredByGroup(): void
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
     */
    public function testResourcesAreFilteredByFacility(): void
    {
        $visibleResource = Resource::factory()->facility()->create();
        $notVisibleResource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('resources?filter[is_facility]=1')
            ->assertSuccessful()
            ->assertSee(route('resources.edit', $visibleResource))
            ->assertDontSee(route('resources.edit', $notVisibleResource));
    }

    /**
     * Resources can be filtered by flags.
     */
    public function testResourcesAreFilteredByFlags(): void
    {
        $status = Status::factory()->create();
        $notVisibleResource = Resource::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get('resources?filter[flags]='.$status->name)
            ->assertSuccessful()
            ->assertSee($status->model->name)
            ->assertDontSee($notVisibleResource->name);
    }
}
