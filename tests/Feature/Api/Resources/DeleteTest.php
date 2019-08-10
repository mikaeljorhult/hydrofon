<?php

namespace Tests\Feature\Api\Resources;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
