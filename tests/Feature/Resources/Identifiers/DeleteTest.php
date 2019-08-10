<?php

namespace Tests\Feature\Resources\Identifiers;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Identifiers can be deleted.
     *
     * @return void
     */
    public function testIdentifiersCanBeDeleted()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->delete('resources/'.$resource->id.'/identifiers/'.$identifier->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('identifiers', [
            'value' => 'test-value',
        ]);
    }

    /**
     * Non-admin users can not delete identifiers.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteIdentifiers()
    {
        $resource = factory(Resource::class)->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs(factory(User::class)->create())->delete('resources/'.$resource->id.'/identifiers/'.$identifier->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
