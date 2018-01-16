<?php

namespace Tests\Feature\Identifiers;

use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Identifiers can be deleted.
     *
     * @return void
     */
    public function testIdentifiersCanBeDeleted()
    {
        $admin      = factory(User::class)->states('admin')->create();
        $user       = factory(User::class)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($admin)->delete('users/' . $user->id . '/identifiers/' . $identifier->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('identifiers', [
            'value' => 'test-value',
        ]);
    }

    /**
     * Non-admin users can not delete identifiers.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotDeleteIdentifiers()
    {
        $user       = factory(User::class)->create();
        $identifier = $user->identifiers()->create(['value' => 'test-value']);

        $response = $this->actingAs($user)->delete('users/' . $user->id . '/identifiers/' . $identifier->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('identifiers', [
            'value' => 'test-value',
        ]);
    }
}
