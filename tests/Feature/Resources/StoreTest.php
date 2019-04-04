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
     * Posts request to persist a group.
     *
     * @param  array  $overrides
     * @param  \Hydrofon\User|null  $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function storeResource($overrides = [], $user = null)
    {
        $resource = factory(Resource::class)->make($overrides);

        return $this->actingAs($user ?: factory(User::class)->states('admin')->create())
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
        $user = factory(User::class)->create();

        $this->storeResource([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Resource::all());
    }
}
