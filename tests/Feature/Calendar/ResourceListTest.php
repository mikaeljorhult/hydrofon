<?php

namespace Tests\Feature\Calendar;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResourceListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resource list is available in calendar view.
     *
     * @return void
     */
    public function testResourceListIsAvailable()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee('resourcelist');
    }

    /**
     * Categories are listed in resource list.
     *
     * @return void
     */
    public function testCategoriesWithoutGroupsAreListed()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /**
     * Categories that have groups are not listed.
     *
     * @return void
     */
    public function testCategoriesWithGroupsAreNotListed()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        $category->groups()->create(['name' => 'Group Name']);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertDontSeeText(e($category->name));
    }

    /**
     * Resources that have groups are listed to user with same group.
     *
     * @return void
     */
    public function testCategoriesWithGroupsAreListedToUsersWithSameGroup()
    {
        $user = factory(User::class)->create();
        $category = factory(Category::class)->create();
        $group = $user->groups()->create(['name' => 'Group Name']);
        $category->groups()->attach($group);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee(e($category->name));
    }

    /**
     * Resources that have no groups are listed.
     *
     * @return void
     */
    public function testResourcesWithoutGroupsAreListed()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSeeText(e($resource->name));
    }

    /**
     * Resources that have groups are not listed.
     *
     * @return void
     */
    public function testResourcesWithGroupsAreNotListed()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();
        $resource->groups()->create(['name' => 'Group Name']);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertDontSeeText($resource->name);
    }

    /**
     * Resources that have groups are listed to user with same group.
     *
     * @return void
     */
    public function testResourcesWithGroupsAreListedToUsersWithSameGroup()
    {
        $user = factory(User::class)->create();
        $resource = factory(Resource::class)->create();
        $group = $user->groups()->create(['name' => 'Group Name']);
        $resource->groups()->attach($group);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee($resource->name);
    }
}
