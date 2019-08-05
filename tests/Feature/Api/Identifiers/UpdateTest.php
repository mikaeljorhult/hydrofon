<?php

namespace Tests\Feature\Api\Identifiers;

use Hydrofon\Resource;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Identifiers can be updated.
     *
     * @return void
     */
    public function testIdentifiersCanBeUpdated()
    {
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->put('api/identifiers/'.$identifier->id,
            [
                'value' => 'Updated Value',
            ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202)
                 ->assertJsonStructure([
                     'type',
                     'id',
                     'value',
                     'identifiable_type',
                     'identifiable_id',
                 ])
                 ->assertJsonFragment([
                     'id'    => $identifier->id,
                     'value' => 'Updated Value',
                 ]);

        $this->assertDatabaseHas('identifiers', [
            'value' => 'Updated Value',
        ]);
    }

    /**
     * Identifiers must have a value.
     *
     * @return void
     */
    public function testIdentifiersMustHaveValue()
    {
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->put('api/identifiers/'.$identifier->id,
            [
                'value' => '',
            ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('value');

        $this->assertDatabaseHas('identifiers', [
            'value' => $identifier->value,
        ]);
    }

    /**
     * Identifiers must have a unique value.
     *
     * @return void
     */
    public function testIdentifiersValueMustBeUnique()
    {
        factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'Current Identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->put('api/identifiers/'.$identifier->id,
            [
                'value' => 'New Identifier',
            ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('value');

        $this->assertDatabaseHas('identifiers', [
            'value' => $identifier->value,
        ]);
    }

    /**
     * Identifiers must have an identifiable type.
     *
     * @return void
     */
    public function testIdentifiersMustHaveAnIdentifiableType()
    {
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->put('api/identifiers/'.$identifier->id,
            [
                'identifiable_type' => '',
            ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_type');

        $this->assertDatabaseHas('identifiers', [
            'identifiable_type' => $identifier->identifiable_type,
        ]);
    }

    /**
     * Identifiable must be of type resource or user.
     *
     * @return void
     */
    public function testIdentifiersMustBeResourceOrUser()
    {
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->put('api/identifiers/'.$identifier->id,
            [
                'identifiable_type' => 'group',
            ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_type');

        $this->assertDatabaseHas('identifiers', [
            'identifiable_type' => $identifier->identifiable_type,
        ]);
    }

    /**
     * Identifiers must have an identifiable ID.
     *
     * @return void
     */
    public function testIdentifiersMustHaveAnIdentifiableID()
    {
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->put('api/identifiers/'.$identifier->id,
            [
                'identifiable_id' => '',
            ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_id');

        $this->assertDatabaseHas('identifiers', [
            'identifiable_id' => $identifier->identifiable_id,
        ]);
    }

    /**
     * Identifiable model must exist.
     *
     * @return void
     */
    public function testIdentifiableMustExist()
    {
        $identifier = factory(Resource::class)->create()->identifiers()->create(['value' => 'New Identifier']);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())->put('api/identifiers/'.$identifier->id,
            [
                'identifiable_type' => 'resource',
                'identifiable_id'   => 100,
            ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('identifiable_id');

        $this->assertDatabaseHas('identifiers', [
            'identifiable_id' => $identifier->identifiable_id,
        ]);
    }
}
