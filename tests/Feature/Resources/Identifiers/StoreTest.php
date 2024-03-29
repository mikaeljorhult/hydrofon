<?php

namespace Tests\Feature\Resources\Identifiers;

use App\Models\Identifier;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Identifiers can be created and stored.
     */
    public function testIdentifiersCanBeStored(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($admin)->post('/resources/'.$resource->id.'/identifiers', [
            'value' => 'test-value',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
            'identifiable_id' => $resource->id,
            'identifiable_type' => \App\Models\Resource::class,
        ]);
    }

    /**
     * Identifiers must have a value.
     */
    public function testIdentifierMustHaveAValue(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($admin)->post('/resources/'.$resource->id.'/identifiers', [
            'value' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertCount(0, Identifier::all());
    }

    /**
     * Value of identifier must be unique.
     */
    public function testValueMustBeUnique(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();
        $admin->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->post('/resources/'.$resource->id.'/identifiers', [
            'value' => 'test-value',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertCount(1, Identifier::all());
    }

    /**
     * Value of identifier can not be a user e-mail address.
     */
    public function testValueCanNotBeAUserEmailAddress(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($admin)->post('/resources/'.$resource->id.'/identifiers', [
            'value' => $resource->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertCount(0, Identifier::all());
    }

    /**
     * Non-admin users can not store identifiers.
     */
    public function testNonAdminUsersCanNotStoreIdentifiers(): void
    {
        $resource = Resource::factory()->create();

        $response = $this->actingAs(User::factory()->create())->post('/resources/'.$resource->id.'/identifiers', [
            'value' => 'test-value',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
