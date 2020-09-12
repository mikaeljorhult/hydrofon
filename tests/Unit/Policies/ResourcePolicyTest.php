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
     *
     * @return void
     */
    public function testOnlyAdminUsersCanViewAResource()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $resource = Resource::factory()->create();

        $this->assertTrue($admin->can('view', $resource));
        $this->assertFalse($user->can('view', $resource));
    }

    /**
     * Only administrators can create resources.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateResources()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Resource::class));
        $this->assertFalse($user->can('create', Resource::class));
    }

    /**
     * Only administrators can update a resource.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanUpdateAResource()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $resource = Resource::factory()->create();

        $this->assertTrue($admin->can('update', $resource));
        $this->assertFalse($user->can('update', $resource));
    }

    /**
     * Only administrators can delete a resource.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanDeleteAResource()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $resource = Resource::factory()->create();

        $this->assertTrue($admin->can('delete', $resource));
        $this->assertFalse($user->can('delete', $resource));
    }

    /**
     * A resource is shown in listings to administrators.
     *
     * @return void
     */
    public function testAResourceCanBeListedIfUserIsAdministrator()
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
     *
     * @return void
     */
    public function testAResourceCanBeListedIfItHasNoGroup()
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $this->assertTrue($user->can('list', $resource));
    }

    /**
     * A resource is shown in listings if it has no group.
     *
     * @return void
     */
    public function testAResourceCanBeListedIfItIsInSameGroupAsUser()
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
