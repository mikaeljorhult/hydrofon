<?php

namespace Tests\Feature\Users\Identifiers;

use App\Models\Identifier;
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
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post('/users/'.$user->id.'/identifiers', [
            'value' => 'test-value',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
            'identifiable_id' => $user->id,
            'identifiable_type' => \App\Models\User::class,
        ]);
    }

    /**
     * Identifiers must have a value.
     */
    public function testIdentifierMustHaveAValue(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post('/users/'.$user->id.'/identifiers', [
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
        $user = User::factory()->create();
        $admin->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->post('/users/'.$user->id.'/identifiers', [
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
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post('/users/'.$user->id.'/identifiers', [
            'value' => $user->email,
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
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/users/'.$user->id.'/identifiers', [
            'value' => 'test-value',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
