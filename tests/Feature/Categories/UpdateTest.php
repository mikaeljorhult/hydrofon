<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Edit route is available.
     */
    public function testEditRouteIsAvailable(): void
    {
        $category = Category::factory()->create();

        $this
            ->actingAs(User::factory()->admin()->create())
            ->get(route('categories.edit', $category))
            ->assertSuccessful()
            ->assertSee($category->name);
    }

    /**
     * Categories can be updated.
     */
    public function testCategoriesCanBeUpdated(): void
    {
        $admin = User::factory()->admin()->create();
        $parents = Category::factory()->times(2)->create();
        $category = Category::factory()->create([
            'parent_id' => $parents[0]->id,
        ]);

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name' => 'New Category Name',
            'parent_id' => $parents[1]->id,
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'name' => 'New Category Name',
            'parent_id' => $parents[1]->id,
        ]);
    }

    /**
     * Categories must have a name.
     */
    public function testCategoriesMustHaveAName(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

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
     */
    public function testParentMustExist(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create([
            'parent_id' => Category::factory()->create()->id,
        ]);

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name' => $category->name,
            'parent_id' => 100,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
            'parent_id' => $category->parent_id,
        ]);
    }

    /**
     * Category can't be its own parent.
     */
    public function testCategoryMustNotBeItsOwnParent(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create([
            'parent_id' => Category::factory()->create()->id,
        ]);

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name' => $category->name,
            'parent_id' => $category->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('parent_id');
        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
            'parent_id' => $category->parent_id,
        ]);
    }

    /**
     * Non-admin user can not update categories.
     */
    public function testNonAdminUsersCanNotUpdateCategories(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->put('categories/'.$category->id, [
            'name' => 'New Category Name',
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $category->name,
        ]);
    }
}
