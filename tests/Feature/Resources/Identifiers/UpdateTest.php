<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Models\Resource;
use App\Models\User;
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
    public function testIdentifiersCanBeUpdated(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();
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
    public function testIdentifierMustHaveAValue(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();
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
    public function testValueMustBeUnique(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();
        $admin->identifiers()->create(['value' => 'another-value']);
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => 'another-value',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
            'id' => $identifier->id,
        ]);
    }

    /**
     * Value can not be a user e-mail address.
     *
     * @return void
     */
    public function testValueCanNotBeAUserEmailAddress(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => $admin->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
            'id' => $identifier->id,
        ]);
    }

    /**
     * Non-admin users can not update identifiers.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateIdentifiers(): void
    {
        $resource = Resource::factory()->create();
        $identifier = $resource->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs(User::factory()->create())->put('resources/'.$resource->id.'/identifiers/'.$identifier->id, [
            'value' => 'another-value',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
