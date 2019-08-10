<?php

namespace Tests\Feature\Api\Resources;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources can be updated.
     *
     * @return void
     */
    public function testResourcesCanBeUpdated()
    {
        $resource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/resources/'.$resource->id, [
                 'name' => 'Updated Name',
             ])
             ->assertStatus(202)
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
                 'id'          => $resource->id,
                 'name'        => 'Updated Name',
                 'description' => $resource->description,
                 'is_facility' => $resource->is_facility,
             ]);

        $this->assertDatabaseHas('resources', [
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Resources must have a name.
     *
     * @return void
     */
    public function testResourcesMustHaveName()
    {
        $resource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/resources/'.$resource->id, [
                 'name' => '',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('resources', [
            'name' => $resource->name,
        ]);
    }
}
