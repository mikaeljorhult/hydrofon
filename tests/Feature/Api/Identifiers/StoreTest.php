<?php

namespace Tests\Feature\Api\Identifiers;

use Hydrofon\Group;
use Hydrofon\Identifier;
use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Identifiers can be created and stored.
     *
     * @return void
     */
    public function testIdentifiersCanBeStored()
    {
        $identifier = [
            'value' => 'New Identifier',
            'identifiable_type' => 'resource',
            'identifiable_id' => factory(Resource::class)->create()->id,
        ];

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/identifiers', $identifier, ['ACCEPT' => 'application/json']);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'type',
                     'id',
                     'value',
                     'identifiable_type',
                     'identifiable_id',
                 ])
                 ->assertJsonFragment([
                     'value'  => $identifier['value'],
                 ]);

        $this->assertDatabaseHas('identifiers', [
            'id'    => 1,
            'value'  => $identifier['value'],
        ]);
    }

    /**
     * Identifiers must have a value.
     *
     * @return void
     */
    public function testIdentifiersMustHaveAValue()
    {
        $identifier = [
            'value' => null,
            'identifiable_type' => 'resource',
            'identifiable_id' => factory(Resource::class)->create()->id,
        ];

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/identifiers', $identifier, ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('value');

        $this->assertEquals(0, \Hydrofon\Identifier::count());
    }

    /**
     * Identifiers must have an identifiable type.
     *
     * @return void
     */
    public function testIdentifiersMustHaveAnIdentifiableType()
    {
        $identifier = [
            'value' => 'New Identifier',
            'identifiable_type' => null,
            'identifiable_id' => factory(Resource::class)->create()->id,
        ];

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/identifiers', $identifier, ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_type');

        $this->assertEquals(0, \Hydrofon\Identifier::count());
    }

    /**
     * Identifiable must be of type resource or user.
     *
     * @return void
     */
    public function testIdentifiableMustBeResourceOrUser()
    {
        $identifier = [
            'value' => 'New Identifier',
            'identifiable_type' => 'group',
            'identifiable_id' => factory(Group::class)->create()->id,
        ];

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/identifiers', $identifier, ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_type');

        $this->assertEquals(0, \Hydrofon\Identifier::count());
    }

    /**
     * Identifiers must have an identifiable ID.
     *
     * @return void
     */
    public function testIdentifiersMustHaveAnIdentifiableID()
    {
        $identifier = [
            'value' => 'New Identifier',
            'identifiable_type' => 'resource',
            'identifiable_id' => null,
        ];

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/identifiers', $identifier, ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_id');

        $this->assertEquals(0, \Hydrofon\Identifier::count());
    }

    /**
     * Identifiable model must exist.
     *
     * @return void
     */
    public function testIdentifiableMustExist()
    {
        $identifier = [
            'value' => 'New Identifier',
            'identifiable_type' => 'resource',
            'identifiable_id' => 100,
        ];

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/identifiers', $identifier, ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_id');

        $this->assertEquals(0, \Hydrofon\Identifier::count());
    }
}
