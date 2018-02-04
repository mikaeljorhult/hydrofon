<?php

namespace Tests\Unit\Policies;

use Hydrofon\Group;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view an object.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanViewAnObject()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $object = factory(Object::class)->create();

        $this->assertTrue($admin->can('view', $object));
        $this->assertFalse($user->can('view', $object));
    }

    /**
     * Only administrators can create objects.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateObjects()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $this->assertTrue($admin->can('create', Object::class));
        $this->assertFalse($user->can('create', Object::class));
    }

    /**
     * Only administrators can update an object.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanUpdateAnObject()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $object = factory(Object::class)->create();

        $this->assertTrue($admin->can('update', $object));
        $this->assertFalse($user->can('update', $object));
    }

    /**
     * Only administrators can delete an object.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanDeleteAnObject()
    {
        $admin = factory(User::class)->states('admin')->create();
        $user = factory(User::class)->create();

        $object = factory(Object::class)->create();

        $this->assertTrue($admin->can('delete', $object));
        $this->assertFalse($user->can('delete', $object));
    }

    /**
     * An object is shown in listings to administrators.
     *
     * @return void
     */
    public function testAnObjectCanBeListedIfUserIsAdministrator()
    {
        $user = factory(User::class)->states('admin')->create();
        $objectWithGroup = factory(Object::class)->create();
        $objectWithoutGroup = factory(Object::class)->create();

        $objectWithGroup->groups()->attach(factory(Group::class)->create());

        $this->assertTrue($user->can('list', $objectWithGroup));
        $this->assertTrue($user->can('list', $objectWithoutGroup));
    }

    /**
     * An object is shown in listings if it has no group.
     *
     * @return void
     */
    public function testAnObjectCanBeListedIfItHasNoGroup()
    {
        $user = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $this->assertTrue($user->can('list', $object));
    }

    /**
     * An object is shown in listings if it has no group.
     *
     * @return void
     */
    public function testAnObjectCanBeListedIfItIsInSameGroupAsUser()
    {
        $userWithGroup = factory(User::class)->create();
        $userWithoutGroup = factory(User::class)->create();
        $userWithOtherGroup = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $userWithGroup->groups()->attach($group = factory(Group::class)->create());
        $userWithOtherGroup->groups()->attach(factory(Group::class)->create());
        $object->groups()->attach($group);

        $this->assertTrue($userWithGroup->can('list', $object));
        $this->assertFalse($userWithoutGroup->can('list', $object));
        $this->assertFalse($userWithOtherGroup->can('list', $object));
    }
}
