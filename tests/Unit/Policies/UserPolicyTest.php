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
     *
     * @return void
     */
    public function testOnlyAdminUsersCanViewAUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $userToView = User::factory()->create();

        $this->assertTrue($admin->can('view', $userToView));
        $this->assertFalse($user->can('view', $userToView));
    }

    /**
     * Only administrators can create users.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateUsers()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', User::class));
        $this->assertFalse($user->can('create', User::class));
    }

    /**
     * Only administrators can update a user.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanUpdateAUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $userToUpdate = User::factory()->create();

        $this->assertTrue($admin->can('update', $userToUpdate));
        $this->assertFalse($user->can('update', $userToUpdate));
    }

    /**
     * Only administrators can delete a user.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanDeleteAUser()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $userToDelete = User::factory()->create();

        $this->assertTrue($admin->can('delete', $userToDelete));
        $this->assertFalse($user->can('delete', $userToDelete));
    }

    /**
     * An administrator can not delete themselves.
     *
     * @return void
     */
    public function testAnAdministratorCanNotDeleteOwnAccount()
    {
        $admin = User::factory()->admin()->create();

        $this->assertFalse($admin->can('delete', $admin));
    }
}
