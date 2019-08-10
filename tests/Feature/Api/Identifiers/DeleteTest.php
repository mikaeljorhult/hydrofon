<?php

namespace Tests\Feature\Api\Identifiers;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
