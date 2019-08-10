<?php

namespace Tests\Feature\Api\Resources;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources can be created and stored.
     *
     * @return void
     */
    public function testResourcesCanBeStored()
    {
        $resource = factory(Resource::class)->make();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->postJson('api/resources', $resource->toArray())
             ->assertStatus(201)
             ->assertJsonStructure([
                 'type',
                 'id',
                 'name',
                 'description',
                 'is_facility',
                 'categories',
             ])
             ->assertJsonFragment([
                 'type'        => 'resource',
                 'id'          => 1,
                 'name'        => $resource->name,
                 'description' => $resource->description,
                 'is_facility' => $resource->is_facility,
             ]);

        $this->assertDatabaseHas('resources', [
            'id'          => 1,
            'name'        => $resource->name,
            'description' => $resource->description,
            'is_facility' => $resource->is_facility,
        ]);
    }

    /**
     * Resources must have a name.
     *
     * @return void
     */
    public function testResourcesMustHaveAName()
    {
        $resource = factory(Resource::class)->make(['name' => null]);

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->postJson('api/resources', $resource->toArray())
             ->assertStatus(422)
             ->assertJsonValidationErrors('name');

        $this->assertEquals(0, \Hydrofon\Resource::count());
    }
}
