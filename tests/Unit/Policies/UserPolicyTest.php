<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view a user.
     */
    public function testOnlyAdminUsersCanViewAUser(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $userToView = User::factory()->create();

        $this->assertTrue($admin->can('view', $userToView));
        $this->assertFalse($user->can('view', $userToView));
    }

    /**
     * Only administrators can create users.
     */
    public function testOnlyAdminUsersCanCreateUsers(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', User::class));
        $this->assertFalse($user->can('create', User::class));
    }

    /**
     * Only administrators can update a user.
     */
    public function testOnlyAdminUsersCanUpdateAUser(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $userToUpdate = User::factory()->create();

        $this->assertTrue($admin->can('update', $userToUpdate));
        $this->assertFalse($user->can('update', $userToUpdate));
    }

    /**
     * Only administrators can delete a user.
     */
    public function testOnlyAdminUsersCanDeleteAUser(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $userToDelete = User::factory()->create();

        $this->assertTrue($admin->can('delete', $userToDelete));
        $this->assertFalse($user->can('delete', $userToDelete));
    }

    /**
     * An administrator can not delete themselves.
     */
    public function testAnAdministratorCanNotDeleteOwnAccount(): void
    {
        $admin = User::factory()->admin()->create();

        $this->assertFalse($admin->can('delete', $admin));
    }
}
