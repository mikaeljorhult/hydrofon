<?php

namespace Tests\Feature\Categories;

use Hydrofon\Category;
use Hydrofon\User;
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
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
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
        $visibleCategory    = factory(Category::class)->create();
        $notVisibleCategory = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
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
        $visibleCategory    = factory(Category::class)->create();
        $notVisibleCategory = factory(Category::class)->create();

        $visibleCategory->parent()->associate(factory(Category::class)->create());
        $visibleCategory->save();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('categories?filter[categories.parent_id]='.$visibleCategory->parent->id)
             ->assertSuccessful()
             ->assertSee(route('categories.edit', $visibleCategory))
             ->assertDontSee(route('categories.edit', $notVisibleCategory));
    }
}
