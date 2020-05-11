<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Resource;
use App\User;
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
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => 'another-value',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('identifiers', [
            'value' => 'another-value',
        ]);
    }

    /**
     * Identifier must have a value.
     *
     * @return void
     */
    public function testIdentifierMustHaveAValue()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
        ]);
    }

    /**
     * Value must be unique.
     *
     * @return void
     */
    public function testValueMustBeUnique()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();
        $admin->identifiers()->create(['value' => 'another-value']);
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => 'another-value',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
            'id'    => $identifier->id,
        ]);
    }

    /**
     * Value can not be a user e-mail address.
     *
     * @return void
     */
    public function testValueCanNotBeAUserEmailAddress()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => $admin->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
            'id'    => $identifier->id,
        ]);
    }

    /**
     * Non-admin users can not update identifiers.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateIdentifiers()
    {
        $resource = factory(Resource::class)->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs(factory(User::class)->create())->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => 'another-value',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
