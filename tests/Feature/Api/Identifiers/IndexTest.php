<?php

namespace Tests\Feature\Api\Identifiers;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Identifiers are listed in index.
     *
     * @return void
     */
    public function testIdentifiersAreListed()
    {
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/identifiers')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'value',
                     ],
                 ],
             ])
             ->assertJsonFragment([
                 'id'    => $identifier->id,
                 'value' => $identifier->value,
             ]);
    }

    /**
     * Identifiers can be filtered by name.
     *
     * @return void
     */
    public function testIdentifiersAreFilteredByValue()
    {
        $excludedIdentifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'Excluded Identifier']);
        $includedIdentifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'Included Identifier']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/identifiers?filter[value]=included')
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $includedIdentifier->id,
             ])
             ->assertJsonMissing([
                 'id' => $excludedIdentifier->id,
             ]);
    }
}
