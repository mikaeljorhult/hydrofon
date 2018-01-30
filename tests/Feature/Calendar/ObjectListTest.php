<?php

namespace Tests\Feature\Calendar;

use Hydrofon\Category;
use Hydrofon\Object;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Object list is available in calendar view.
     *
     * @return void
     */
    public function testObjectListIsAvailable()
    {
        $user     = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertSee('objectlist');
    }

    /**
     * Categories are listed in object list.
     *
     * @return void
     */
    public function testCategoriesAreListed()
    {
        $user     = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /**
     * Objects that have no groups are listed.
     *
     * @return void
     */
    public function testObjectsWithoutGroupsAreListed()
    {
        $user   = factory(User::class)->create();
        $object = factory(Object::class)->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertSee($object->name);
    }

    /**
     * Objects that have groups are not listed.
     *
     * @return void
     */
    public function testObjectsWithGroupsAreNotListed()
    {
        $user   = factory(User::class)->create();
        $object = factory(Object::class)->create();
        $object->groups()->create(['name' => 'Group Name']);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertDontSee($object->name);
    }

    /**
     * Objects that have groups are listed to user with same group.
     *
     * @return void
     */
    public function testObjectsWithGroupsAreListedToUsersWithSameGroup()
    {
        $user   = factory(User::class)->create();
        $object = factory(Object::class)->create();
        $group  = $user->groups()->create(['name' => 'Group Name']);
        $object->groups()->attach($group);

        $response = $this->actingAs($user)->get('/home');

        $response->assertStatus(200);
        $response->assertSee($object->name);
    }
}
