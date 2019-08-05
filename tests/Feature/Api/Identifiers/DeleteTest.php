<?php

namespace Tests\Feature\Api\Identifiers;

use Hydrofon\Identifier;
use Hydrofon\Resource;
use Hydrofon\User;
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
        $admin = factory(User::class)->states('admin')->create();
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $response = $this->actingAs($admin)->delete('api/identifiers/'.$identifier->id, ['ACCEPT' => 'application/json']);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('identifiers', [
            'id' => $identifier->id,
        ]);
    }
}
