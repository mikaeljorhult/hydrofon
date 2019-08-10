<?php

namespace Tests\Feature\Categories;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories can be updated.
     *
     * @return void
     */
    public function testCategoriesCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $parents = factory(Category::class, 2)->create();
        $category = factory(Category::class)->create([
            'parent_id' => $parents[0]->id,
        ]);

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name'      => 'New Category Name',
            'parent_id' => $parents[1]->id,
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'name'      => 'New Category Name',
            'parent_id' => $parents[1]->id,
        ]);
    }

    /**
     * Categories must have a name.
     *
     * @return void
     */
    public function testCategoriesMustHaveAName()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
        ]);
    }

    /**
     * A parent category must exist in the database.
     *
     * @return void
     */
    public function testParentMustExist()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create([
            'parent_id' => factory(Category::class)->create()->id,
        ]);

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name'      => $category->name,
            'parent_id' => 100,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseHas('categories', [
            'name'      => $category->name,
            'parent_id' => $category->parent_id,
        ]);
    }

    /**
     * Category can't be its own parent.
     *
     * @return void
     */
    public function testCategoryMustNotBeItsOwnParent()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create([
            'parent_id' => factory(Category::class)->create()->id,
        ]);

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name'      => $category->name,
            'parent_id' => $category->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseHas('categories', [
            'name'      => $category->name,
            'parent_id' => $category->parent_id,
        ]);
    }

    /**
     * Non-admin user can not update categories.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotUpdateCategories()
    {
        $admin = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name' => 'New Category Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('categories', [
            'id'   => $category->id,
            'name' => $category->name,
        ]);
    }
}
