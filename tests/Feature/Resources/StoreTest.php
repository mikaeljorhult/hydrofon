<?php

namespace Tests\Feature\Resources;

use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a group.
     *
     * @param array               $overrides
     * @param \App\User|null $user
     *
     * @return \Illuminate\Testing\TestResponse
     */
    public function storeResource($overrides = [], $user = null)
    {
        $resource = Resource::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->post('resources', $resource->toArray());
    }

    /**
     * Resources can be created and stored.
     *
     * @return void
     */
    public function testResourcesCanBeStored()
    {
        $this->storeResource(['name' => 'New Resource'])
             ->assertRedirect('/resources');

        $this->assertDatabaseHas('resources', [
            'name' => 'New Resource',
        ]);
    }

    /**
     * Resources must have a name.
     *
     * @return void
     */
    public function testResourcesMustHaveAName()
    {
        $this->storeResource(['name' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('name');

        $this->assertCount(0, Resource::all());
    }

    /**
     * Non-admin users can not store resources.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreResources()
    {
        $user = User::factory()->create();

        $this->storeResource([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Resource::all());
    }
}
