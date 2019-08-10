<?php

namespace Tests\Feature\Api\Identifiers;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/identifiers/'.$identifier->id, [
                 'value' => 'Updated Value',
             ])
             ->assertStatus(202)
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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/identifiers/'.$identifier->id, [
                 'value' => '',
             ])
             ->assertStatus(422)
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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/identifiers/'.$identifier->id, [
                 'value' => 'New Identifier',
             ])
             ->assertStatus(422)
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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/identifiers/'.$identifier->id, [
                 'identifiable_type' => '',
             ])
             ->assertStatus(422)
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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/identifiers/'.$identifier->id, [
                 'identifiable_type' => 'group',
             ])
             ->assertStatus(422)
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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/identifiers/'.$identifier->id, [
                 'identifiable_id' => '',
             ])
             ->assertStatus(422)
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

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/identifiers/'.$identifier->id, [
                 'identifiable_type' => 'resource',
                 'identifiable_id'   => 100,
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors('identifiable_id');

        $this->assertDatabaseHas('identifiers', [
            'identifiable_id' => $identifier->identifiable_id,
        ]);
    }
}
