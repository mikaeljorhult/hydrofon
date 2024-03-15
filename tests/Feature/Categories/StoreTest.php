<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Posts request to persist a category.
     */
    public function storeCategory(array $overrides = [], ?User $user = null): TestResponse
    {
        $category = Category::factory()->make($overrides);

        return $this->actingAs($user ?: User::factory()->admin()->create())
            ->post('categories', $category->toArray());
    }

    /**
     * Categories can be created and stored.
     */
    public function testCategoriesCanBeStored(): void
    {
        $parent = Category::factory()->create();

        $this->storeCategory([
            'name' => 'New Category',
            'parent_id' => $parent->id,
        ])
            ->assertRedirect('/categories');

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Non-admin users can not store categories.
     */
    public function testNonAdminUsersCanNotStoreCategories(): void
    {
        $user = User::factory()->create();

        $this->storeCategory([], $user)
            ->assertStatus(403);

        $this->assertCount(0, Category::all());
    }

    /**
     * Categories must have a name.
     */
    public function testCategoriesMustHaveAName(): void
    {
        $this->storeCategory(['name' => null])
            ->assertRedirect()
            ->assertSessionHasErrors('name');

        $this->assertCount(0, Category::all());
    }

    /**
     * A parent category must exist in the database.
     */
    public function testParentMustExist(): void
    {
        $category = Category::factory()->make();

        $this->actingAs(User::factory()->admin()->create())
            ->post('categories', array_merge($category->toArray(), ['parent_id' => 100]))
            ->assertRedirect()
            ->assertSessionHasErrors('parent_id');

        $this->assertCount(0, Category::all());
    }
}
