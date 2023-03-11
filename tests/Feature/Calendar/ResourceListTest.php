<?php

namespace Tests\Feature\Calendar;

use App\Models\Category;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Resource list is available in calendar view.
     *
     * @return void
     */
    public function testResourceListIsAvailable(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee('resourcelist');
    }

    /**
     * Categories are listed in resource list.
     *
     * @return void
     */
    public function testCategoriesWithoutGroupsAreListed(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /**
     * Categories that have groups are not listed.
     *
     * @return void
     */
    public function testCategoriesWithGroupsAreNotListed(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $category->groups()->create(['name' => 'Group Name']);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertDontSeeText($category->name);
    }

    /**
     * Resources that have groups are listed to user with same group.
     *
     * @return void
     */
    public function testCategoriesWithGroupsAreListedToUsersWithSameGroup(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $group = $user->groups()->create(['name' => 'Group Name']);
        $category->groups()->attach($group);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee($category->name);
    }

    /**
     * Resources that have no groups are listed.
     *
     * @return void
     */
    public function testResourcesWithoutGroupsAreListed(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSeeText($resource->name);
    }

    /**
     * Resources that have groups are not listed.
     *
     * @return void
     */
    public function testResourcesWithGroupsAreNotListed(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();
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
    public function testResourcesWithGroupsAreListedToUsersWithSameGroup(): void
    {
        $user = User::factory()->create();
        $resource = Resource::factory()->create();
        $group = $user->groups()->create(['name' => 'Group Name']);
        $resource->groups()->attach($group);

        $response = $this->actingAs($user)->get('/calendar');

        $response->assertStatus(200);
        $response->assertSee($resource->name);
    }
}
