<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Resource;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();
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
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs(User::factory()->create())->delete('resources/'.$resource->id.'/identifiers/'.$identifier->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
