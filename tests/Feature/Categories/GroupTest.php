<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Group relationships are stored when creating a category.
     *
     * @return void
     */
    public function testGroupRelationshipsAreStoredWhenCreatingCategory()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $category = Category::factory()->make();

        $this->actingAs($admin)->post('categories', [
            'name'   => $category->name,
            'groups' => [$group->id],
        ]);

        $this->assertDatabaseHas('category_group', [
            'category_id' => 1,
            'group_id'    => $group->id,
        ]);
    }

    /**
     * Non-existing groups cannot be added when creating a category.
     *
     * @return void
     */
    public function testNonExistingGroupsCannotBeAddedWhenStoringCategory()
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->make();

        $response = $this->actingAs($admin)->post('categories', [
            'name'   => $category->name,
            'groups' => [100],
        ]);

        $response->assertSessionHasErrors('groups.*');
        $this->assertDatabaseMissing('category_group', [
            'group_id' => 100,
        ]);
    }

    /**
     * Group relationships are stored when updating a category.
     *
     * @return void
     */
    public function testGroupRelationshipsAreStoredWhenUpdatingCategory()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)->put('categories/'.$category->id, [
            'name'   => 'New Category Name',
            'groups' => [$group->id],
        ]);

        $this->assertDatabaseHas('category_group', [
            'category_id' => $category->id,
            'group_id'    => $group->id,
        ]);
    }

    /**
     * Group relationships are removed when updating user.
     *
     * @return void
     */
    public function testGroupRelationshipsAreRemovedWhenUpdatingCategory()
    {
        $admin = User::factory()->admin()->create();
        $group = Group::factory()->create();
        $category = Category::factory()->create();

        $category->groups()->attach($group);

        $this->assertDatabaseHas('category_group', [
            'category_id' => $category->id,
            'group_id'    => $group->id,
        ]);

        $this->actingAs($admin)->put('categories/'.$category->id, [
            'name'   => 'New Category Name',
            'groups' => [],
        ]);

        $this->assertDatabaseMissing('category_group', [
            'category_id' => $category->id,
            'group_id'    => $group->id,
        ]);
    }

    /**
     * Non-existing groups cannot be added when updating a category.
     *
     * @return void
     */
    public function testNonExistingGroupsCannotBeAddedWhenUpdatingCategory()
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name'   => 'New Category Name',
            'groups' => [100],
        ]);

        $response->assertSessionHasErrors('groups.*');
        $this->assertDatabaseMissing('category_group', [
            'group_id' => 100,
        ]);
    }
}
