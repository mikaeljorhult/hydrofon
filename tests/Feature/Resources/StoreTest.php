<?php

namespace Tests\Feature\Resources;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->make();

        $response = $this->actingAs($admin)->post('resources', [
            'name' => $resource->name,
        ]);

        $response->assertRedirect('/resources');
        $this->assertDatabaseHas('resources', [
            'name' => $resource->name,
        ]);
    }

    /**
     * Resources must have a name.
     *
     * @return void
     */
    public function testResourcesMustHaveAName()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)->post('resources', [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertCount(0, Resource::all());
    }

    /**
     * Non-admin users can not store resources.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreResources()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->make();

        $response = $this->actingAs($user)->post('resources', [
            'name' => $resource->name,
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('resources', [
            'name' => $resource->name,
        ]);
    }
}
