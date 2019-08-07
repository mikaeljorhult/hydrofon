<?php

namespace Tests\Feature\Api\Identifiers;

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
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->deleteJson('api/identifiers/'.$identifier->id)
             ->assertStatus(204);

        $this->assertDatabaseMissing('identifiers', [
            'id' => $identifier->id,
        ]);
    }
}
