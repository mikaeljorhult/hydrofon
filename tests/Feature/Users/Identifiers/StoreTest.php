<?php

namespace Tests\Feature\Users\Identifiers;

use App\Identifier;
use App\User;
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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->post('/users/'.$user->id.'/identifiers', [
            'value' => 'test-value',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('identifiers', [
            'value'             => 'test-value',
            'identifiable_id'   => $user->id,
            'identifiable_type' => \App\User::class,
        ]);
    }

    /**
     * Identifiers must have a value.
     *
     * @return void
     */
    public function testIdentifierMustHaveAValue()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->post('/users/'.$user->id.'/identifiers', [
            'value' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertCount(0, Identifier::all());
    }

    /**
     * Value of identifier must be unique.
     *
     * @return void
     */
    public function testValueMustBeUnique()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();
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
     *
     * @return void
     */
    public function testValueCanNotBeAUserEmailAddress()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $response = $this->actingAs($admin)->post('/users/'.$user->id.'/identifiers', [
            'value' => $user->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('value');
        $this->assertCount(0, Identifier::all());
    }

    /**
     * Non-admin users can not store identifiers.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreIdentifiers()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/users/'.$user->id.'/identifiers', [
            'value' => 'test-value',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
