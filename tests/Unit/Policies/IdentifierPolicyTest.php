<?php

namespace Tests\Unit\Policies;

use App\Models\Identifier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdentifierPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view an identifier.
     */
    public function testOnlyAdminUsersCanViewAnIdentifier(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $identifier = User::factory()->create()->identifiers()->create(['value' => 'identifier']);

        $this->assertTrue($admin->can('view', $identifier));
        $this->assertFalse($user->can('view', $identifier));
    }

    /**
     * Only administrators can create identifiers.
     */
    public function testOnlyAdminUsersCanCreateIdentifiers(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Identifier::class));
        $this->assertFalse($user->can('create', Identifier::class));
    }

    /**
     * Only administrators can update an identifier.
     */
    public function testOnlyAdminUsersCanUpdateAnIdentifier(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $identifier = User::factory()->create()->identifiers()->create(['value' => 'identifier']);

        $this->assertTrue($admin->can('update', $identifier));
        $this->assertFalse($user->can('update', $identifier));
    }

    /**
     * Only administrators can delete an identifier.
     */
    public function testOnlyAdminUsersCanDeleteAnIdentifier(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $identifier = User::factory()->create()->identifiers()->create(['value' => 'identifier']);

        $this->assertTrue($admin->can('delete', $identifier));
        $this->assertFalse($user->can('delete', $identifier));
    }
}
