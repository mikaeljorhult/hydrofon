<?php

namespace Tests\Feature\Resources;

use App\Models\Resource;
use App\Models\User;
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
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

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
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

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
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

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
