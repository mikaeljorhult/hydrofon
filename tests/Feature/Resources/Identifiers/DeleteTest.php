<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Identifiers can be deleted.
     */
    public function testIdentifiersCanBeDeleted(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->delete('resources/'.$resource->id.'/identifiers/'.$identifier->id);

        $response->assertRedirect();
        $this->assertModelMissing($identifier);
    }

    /**
     * Non-admin users can not delete identifiers.
     */
    public function testNonAdminUsersCanNotDeleteIdentifiers(): void
    {
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs(User::factory()->create())->delete('resources/'.$resource->id.'/identifiers/'.$identifier->id);

        $response->assertStatus(403);
        $this->assertModelExists($identifier);
    }
}
