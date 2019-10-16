<?php

namespace Tests\Feature\Api\Resources;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resources can be deleted.
     *
     * @return void
     */
    public function testResourcesCanBeDeleted()
    {
        $resource = factory(Resource::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->deleteJson('api/resources/'.$resource->id)
             ->assertStatus(204);

        $this->assertDatabaseMissing('resources', [
            'id' => $resource->id,
        ]);
    }
}
