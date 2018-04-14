<?php

namespace Tests\Feature\Resources;

use Hydrofon\Resource;
use Hydrofon\User;
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
        $resource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('resources')
             ->assertSuccessful()
             ->assertSee($resource->name);
    }

    /**
     * Resources index can be filtered by name.
     *
     * @return void
     */
    public function testResourcesCanBeFilteredByName()
    {
        $visibleResource = factory(Resource::class)->create();
        $notVisibleResource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('resources?'.http_build_query([
                     'filter' => $visibleResource->name,
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleResource->name)
             ->assertDontSee($notVisibleResource->name);
    }
}
