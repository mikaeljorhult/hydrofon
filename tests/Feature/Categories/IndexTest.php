<?php

namespace Tests\Feature\Categories;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories are listed in index.
     *
     * @return void
     */
    public function testCategoriesAreListed()
    {
        $category = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('categories')
             ->assertSuccessful()
             ->assertSee($category->name);
    }

    /**
     * Categories can be filtered by the name.
     *
     * @return void
     */
    public function testCategoriesAreFilteredByName()
    {
        $visibleCategory = Category::factory()->create();
        $notVisibleCategory = Category::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
             ->get('categories?filter[categories.name]='.$visibleCategory->name)
             ->assertSuccessful()
             ->assertSee(route('categories.edit', $visibleCategory))
             ->assertDontSee(route('categories.edit', $notVisibleCategory));
    }

    /**
     * Categories can be filtered by parent category.
     *
     * @return void
     */
    public function testCategoriesAreFilteredByParent()
    {
        $visibleCategory = Category::factory()->create();
        $notVisibleCategory = Category::factory()->create();

        $visibleCategory->parent()->associate(Category::factory()->create());
        $visibleCategory->save();

        $this->actingAs(User::factory()->admin()->create())
             ->get('categories?filter[categories.parent_id]='.$visibleCategory->parent->id)
             ->assertSuccessful()
             ->assertSee(route('categories.edit', $visibleCategory))
             ->assertDontSee(route('categories.edit', $notVisibleCategory));
    }
}
