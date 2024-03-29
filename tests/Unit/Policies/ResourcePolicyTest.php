<?php

namespace Tests\Unit\Policies;

use App\Models\Group;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourcePolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view a resource.
     */
    public function testOnlyAdminUsersCanViewAResource(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $resource = Resource::factory()->create();

        $this->assertTrue($admin->can('view', $resource));
        $this->assertFalse($user->can('view', $resource));
    }

    /**
     * Only administrators can create resources.
     */
    public function testOnlyAdminUsersCanCreateResources(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Resource::class));
        $this->assertFalse($user->can('create', Resource::class));
    }

    /**
     * Only administrators can update a resource.
     */
    public function testOnlyAdminUsersCanUpdateAResource(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $resource = Resource::factory()->create();

        $this->assertTrue($admin->can('update', $resource));
        $this->assertFalse($user->can('update', $resource));
    }

    /**
     * Only administrators can delete a resource.
     */
    public function testOnlyAdminUsersCanDeleteAResource(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $resource = Resource::factory()->create();

        $this->assertTrue($admin->can('delete', $resource));
        $this->assertFalse($user->can('delete', $resource));
    }

    /**
     * A resource is shown in listings to administrators.
     */
    public function testAResourceCanBeListedIfUserIsAdministrator(): void
    {
        $user = User::factory()->admin()->create();
        $resourceWithGroup = Resource::factory()->create();
        $resourceWithoutGroup = Resource::factory()->create();

        $resourceWithGroup->groups()->attach(Group::factory()->create());

        $this->assertTrue($user->can('list', $resourceWithGroup));
        $this->assertTrue($user->can('list', $resourceWithoutGroup));
    }

    /**
     * A resource is shown in listings if it has no group.
     */
    public function testAResourceCanBeListedIfItHasNoGroup(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $this->assertTrue($user->can('list', $resource));
    }

    /**
     * A resource is shown in listings if it has no group.
     */
    public function testAResourceCanBeListedIfItIsInSameGroupAsUser(): void
    {
        $userWithGroup = User::factory()->create();
        $userWithoutGroup = User::factory()->create();
        $userWithOtherGroup = User::factory()->create();
        $resource = Resource::factory()->create();

        $userWithGroup->groups()->attach($group = Group::factory()->create());
        $userWithOtherGroup->groups()->attach(Group::factory()->create());
        $resource->groups()->attach($group);

        $this->assertTrue($userWithGroup->can('list', $resource));
        $this->assertFalse($userWithoutGroup->can('list', $resource));
        $this->assertFalse($userWithOtherGroup->can('list', $resource));
    }
}
