<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories are listed in index.
     */
    public function testCategoriesAreListed(): void
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('categories')
             ->assertSuccessful()
             ->assertSee($category->name);
    }

    /**
     * Categories can be filtered by the name.
     */
    public function testCategoriesAreFilteredByName(): void
    {
        $visibleCategory = Category::factory()->create();
        $notVisibleCategory = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('categories?filter[name]='.$visibleCategory->name)
             ->assertSuccessful()
             ->assertSee(route('categories.edit', $visibleCategory))
             ->assertDontSee(route('categories.edit', $notVisibleCategory));
    }

    /**
     * Categories can be filtered by parent category.
     */
    public function testCategoriesAreFilteredByParent(): void
    {
        $visibleCategory = Category::factory()->create();
        $notVisibleCategory = Category::factory()->create();

        $visibleCategory->parent()->associate(Category::factory()->create());
        $visibleCategory->save();

        $this->actingAs(User::factory()->admin()->create())
             ->get('categories?filter[parent_id]='.$visibleCategory->parent->id)
             ->assertSuccessful()
             ->assertSee(route('categories.edit', $visibleCategory))
             ->assertDontSee(route('categories.edit', $notVisibleCategory));
    }
}
