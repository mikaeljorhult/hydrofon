<?php

namespace Tests\Unit\Model;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Category can have a parent.
     *
     * @return void
     */
    public function testCategoryCanHaveAParent()
    {
        $this->actingAs(factory(User::class)->states('admin')->create());

        $category = factory(Category::class)->states('child')->create();

        $this->assertInstanceOf(Category::class, $category->parent);
    }

    /**
     * Category can have child categories.
     *
     * @return void
     */
    public function testCategoryCanHaveChildCategories()
    {
        $this->actingAs(factory(User::class)->states('admin')->create());

        $category = factory(Category::class)->create();

        $this->assertInstanceOf(Collection::class, $category->categories);
    }

    /**
     * Category can have child resources.
     *
     * @return void
     */
    public function testCategoryCanHaveChildResources()
    {
        $this->actingAs(factory(User::class)->states('admin')->create());

        $category = factory(Category::class)->create();

        $this->assertInstanceOf(Collection::class, $category->resources);
    }
}
