<?php

namespace Tests\Feature\Categories;

use Hydrofon\User;
use Hydrofon\Group;
use Tests\TestCase;
use Hydrofon\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();
        $category = factory(Category::class)->make();

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
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->make();

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
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();
        $category = factory(Category::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $group = factory(Group::class)->create();
        $category = factory(Category::class)->create();

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
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

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
