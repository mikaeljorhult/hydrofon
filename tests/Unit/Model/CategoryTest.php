<?php

namespace Tests\Unit\Model;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Category can have a parent.
     */
    public function testCategoryCanHaveAParent(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $category = Category::factory()->child()->create();

        $this->assertInstanceOf(Category::class, $category->parent);
    }

    /**
     * Category can have child categories.
     */
    public function testCategoryCanHaveChildCategories(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $category = Category::factory()->create();

        $this->assertInstanceOf(Collection::class, $category->categories);
    }

    /**
     * Category can have child resources.
     */
    public function testCategoryCanHaveChildResources(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $category = Category::factory()->create();

        $this->assertInstanceOf(Collection::class, $category->resources);
    }
}
