<?php

namespace Tests\Unit\Policies;

use Hydrofon\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $userToView = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $userToUpdate = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $userToDelete = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();

        $this->assertFalse($admin->can('delete', $admin));
    }
}
