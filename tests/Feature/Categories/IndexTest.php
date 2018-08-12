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
     * Categories index can be filtered by name.
     *
     * @return void
     */
    public function testCategoriesCanBeFilteredByName()
    {
        $visibleCategory = factory(Category::class)->create();
        $notVisibleCategory = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->get('categories?'.http_build_query([
                     'filter[categories.name]' => $visibleCategory->name,
                 ]))
             ->assertSuccessful()
             ->assertSee($visibleCategory->name)
             ->assertDontSee($notVisibleCategory->name);
    }
}
