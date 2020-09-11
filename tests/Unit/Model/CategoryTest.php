<?php

namespace Tests\Unit\Model;

use App\Category;
use App\User;
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
        $this->actingAs(User::factory()->admin()->create());

        $category = Category::factory()->child()->create();

        $this->assertInstanceOf(Category::class, $category->parent);
    }

    /**
     * Category can have child categories.
     *
     * @return void
     */
    public function testCategoryCanHaveChildCategories()
    {
        $this->actingAs(User::factory()->admin()->create());

        $category = Category::factory()->create();

        $this->assertInstanceOf(Collection::class, $category->categories);
    }

    /**
     * Category can have child resources.
     *
     * @return void
     */
    public function testCategoryCanHaveChildResources()
    {
        $this->actingAs(User::factory()->admin()->create());

        $category = Category::factory()->create();

        $this->assertInstanceOf(Collection::class, $category->resources);
    }
}
