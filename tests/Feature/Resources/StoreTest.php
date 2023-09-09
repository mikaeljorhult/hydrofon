<?php

namespace Tests\Feature\Resources;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a group.
     */
    public function storeResource(array $overrides = [], User $user = null): TestResponse
    {
        $resource = Resource::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
            ->post('resources', $resource->toArray());
    }

    /**
     * Resources can be created and stored.
     */
    public function testResourcesCanBeStored(): void
    {
        $this->storeResource(['name' => 'New Resource'])
            ->assertRedirect('/resources');

        $this->assertDatabaseHas('resources', [
            'name' => 'New Resource',
        ]);
    }

    /**
     * Resources must have a name.
     */
    public function testResourcesMustHaveAName(): void
    {
        $this->storeResource(['name' => null])
            ->assertRedirect()
            ->assertSessionHasErrors('name');

        $this->assertCount(0, Resource::all());
    }

    /**
     * Non-admin users can not store resources.
     */
    public function testNonAdminUsersCanNotStoreResources(): void
    {
        $user = User::factory()->create();

        $this->storeResource([], $user)
            ->assertStatus(403);

        $this->assertCount(0, Resource::all());
    }
}
