<?php

namespace Tests\Unit\Policies;

use Hydrofon\User;
use Hydrofon\Group;
use Tests\TestCase;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $resource = factory(Resource::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $resource = factory(Resource::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $resource = factory(Resource::class)->create();

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
        $user = factory(User::class)->states('admin')->create();
        $resourceWithGroup = factory(Resource::class)->create();
        $resourceWithoutGroup = factory(Resource::class)->create();

        $resourceWithGroup->groups()->attach(factory(Group::class)->create());

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
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $this->assertTrue($user->can('list', $resource));
    }

    /**
     * A resource is shown in listings if it has no group.
     *
     * @return void
     */
    public function testAResourceCanBeListedIfItIsInSameGroupAsUser()
    {
        $userWithGroup = factory(User::class)->create();
        $userWithoutGroup = factory(User::class)->create();
        $userWithOtherGroup = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $userWithGroup->groups()->attach($group = factory(Group::class)->create());
        $userWithOtherGroup->groups()->attach(factory(Group::class)->create());
        $resource->groups()->attach($group);

        $this->assertTrue($userWithGroup->can('list', $resource));
        $this->assertFalse($userWithoutGroup->can('list', $resource));
        $this->assertFalse($userWithOtherGroup->can('list', $resource));
    }
}
