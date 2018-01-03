<?php

namespace Tests\Unit\Model;

use Hydrofon\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
        $category = factory(Category::class)->create();

        $this->assertInstanceOf(Collection::class, $category->categories);
    }

    /**
     * Category can have child categories.
     *
     * @return void
     */
    public function testCategoryCanHaveChildObjects()
    {
        $category = factory(Category::class)->create();

        $this->assertInstanceOf(Collection::class, $category->objects);
    }
}
