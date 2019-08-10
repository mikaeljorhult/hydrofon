<?php

namespace Tests\Feature\Desk;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResourceDeskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources can be found by an identifier.
     *
     * @return void
     */
    public function testResourcesCanBeFoundByIdentifier()
    {
        $resource = factory(Resource::class)->create();
        $resource->identifiers()->create(['value' => 'resource-identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->get('/desk/resource-identifier');

        $response->assertStatus(200);
        $response->assertSee(e($resource->name));
    }
}
