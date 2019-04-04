<?php

namespace Tests\Feature\Identifiers;

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
        $admin      = factory(User::class)->states('admin')->create();
        $user       = factory(User::class)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('users/'.$user->id.'/identifiers/'.$identifier->id, [
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
        $admin      = factory(User::class)->states('admin')->create();
        $user       = factory(User::class)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('users/'.$user->id.'/identifiers/'.$identifier->id, [
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
        $user  = factory(User::class)->create();
        $admin->identifiers()->create(['value' => 'another-value']);
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('users/'.$user->id.'/identifiers/'.$identifier->id, [
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
        $admin      = factory(User::class)->states('admin')->create();
        $user       = factory(User::class)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->put('users/'.$user->id.'/identifiers/'.$identifier->id, [
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
        $user       = factory(User::class)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($user)->put('users/'.$user->id.'/identifiers/'.$identifier->id, [
            'value' => 'another-value',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
