<?php

namespace Tests\Feature\Api\Resources;

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

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/resources')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'type',
                         'id',
                         'name',
                         'description',
                         'is_facility',
                         'categories',
                     ],
                 ],
             ])
             ->assertJsonFragment([
                 'type'        => 'resource',
                 'id'          => $resource->id,
                 'name'        => $resource->name,
                 'description' => $resource->description,
                 'is_facility' => $resource->is_facility,
             ]);
    }

    /**
     * Resources can be filtered by name.
     *
     * @return void
     */
    public function testResourcesAreFilteredByName()
    {
        $excludedResource = factory(Resource::class)->create(['name' => 'Excluded Resource']);
        $includedResource = factory(Resource::class)->create(['name' => 'Included Resource']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/resources?filter[name]=included')
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $includedResource->id,
             ])
             ->assertJsonMissing([
                 'id' => $excludedResource->id,
             ]);
    }
}
