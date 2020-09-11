<?php

namespace Tests\Feature\Categories;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a category.
     *
     * @param array               $overrides
     * @param \App\User|null $user
     *
     * @return \Illuminate\Testing\TestResponse
     */
    public function storeCategory($overrides = [], $user = null)
    {
        $category = Category::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
                    ->post('categories', $category->toArray());
    }

    /**
     * Categories can be created and stored.
     *
     * @return void
     */
    public function testCategoriesCanBeStored()
    {
        $parent = Category::factory()->create();

        $this->storeCategory([
            'name'      => 'New Category',
            'parent_id' => $parent->id,
        ])
             ->assertRedirect('/categories');

        $this->assertDatabaseHas('categories', [
            'name'      => 'New Category',
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Non-admin users can not store categories.
     *
     * @return void
     */
    public function testNonAdminUsersCanNotStoreCategories()
    {
        $user = User::factory()->create();

        $this->storeCategory([], $user)
             ->assertStatus(403);

        $this->assertCount(0, Category::all());
    }

    /**
     * Categories must have a name.
     *
     * @return void
     */
    public function testCategoriesMustHaveAName()
    {
        $this->storeCategory(['name' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('name');

        $this->assertCount(0, Category::all());
    }

    /**
     * A parent category must exist in the database.
     *
     * @return void
     */
    public function testParentMustExist()
    {
        $category = Category::factory()->make();

        $this->actingAs(User::factory()->admin()->create())
             ->post('categories', array_merge($category->toArray(), ['parent_id' => 100]))
             ->assertRedirect()
             ->assertSessionHasErrors('parent_id');

        $this->assertCount(0, Category::all());
    }
}
