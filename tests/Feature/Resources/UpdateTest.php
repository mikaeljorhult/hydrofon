<?php

namespace Tests\Feature\Resources;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name' => 'New Resource Name',
        ]);

        $response->assertRedirect('/resources');
        $this->assertDatabaseHas('resources', [
            'name' => 'New Resource Name',
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
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('resources', [
            'name' => $resource->name,
        ]);
    }

    /**
     * Non-admin users can not update resources.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateResources()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($user)->put('resources/'.$resource->id, [
            'name' => 'New Resource Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('resources', [
            'id'   => $resource->id,
            'name' => $resource->name,
        ]);
    }
}
