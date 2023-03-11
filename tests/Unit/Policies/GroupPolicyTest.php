<?php

namespace Tests\Unit\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view a group.
     */
    public function testOnlyAdminUsersCanViewAGroup(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $group = Group::factory()->create();

        $this->assertTrue($admin->can('view', $group));
        $this->assertFalse($user->can('view', $group));
    }

    /**
     * Only administrators can create groups.
     */
    public function testOnlyAdminUsersCanCreateGroups(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Group::class));
        $this->assertFalse($user->can('create', Group::class));
    }

    /**
     * Only administrators can update a group.
     */
    public function testOnlyAdminUsersCanUpdateAGroup(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $group = Group::factory()->create();

        $this->assertTrue($admin->can('update', $group));
        $this->assertFalse($user->can('update', $group));
    }

    /**
     * Only administrators can delete a group.
     */
    public function testOnlyAdminUsersCanDeleteAGroup(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $group = Group::factory()->create();

        $this->assertTrue($admin->can('delete', $group));
        $this->assertFalse($user->can('delete', $group));
    }
}
