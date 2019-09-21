<?php

namespace Tests\Unit\Policies;

use Hydrofon\Group;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view a group.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanViewAGroup()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $group = factory(Group::class)->create();

        $this->assertTrue($admin->can('view', $group));
        $this->assertFalse($user->can('view', $group));
    }

    /**
     * Only administrators can create groups.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateGroups()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->assertTrue($admin->can('create', Group::class));
        $this->assertFalse($user->can('create', Group::class));
    }

    /**
     * Only administrators can update a group.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanUpdateAGroup()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $group = factory(Group::class)->create();

        $this->assertTrue($admin->can('update', $group));
        $this->assertFalse($user->can('update', $group));
    }

    /**
     * Only administrators can delete a group.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanDeleteAGroup()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $group = factory(Group::class)->create();

        $this->assertTrue($admin->can('delete', $group));
        $this->assertFalse($user->can('delete', $group));
    }
}
