<?php

namespace Tests\Unit\Policies;

use App\Category;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view a category.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanViewACategory()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $this->assertTrue($admin->can('view', $category));
        $this->assertFalse($user->can('view', $category));
    }

    /**
     * Only administrators can create categories.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanCreateCategories()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Category::class));
        $this->assertFalse($user->can('create', Category::class));
    }

    /**
     * Only administrators can update a category.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanUpdateACategory()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $this->assertTrue($admin->can('update', $category));
        $this->assertFalse($user->can('update', $category));
    }

    /**
     * Only administrators can delete a category.
     *
     * @return void
     */
    public function testOnlyAdminUsersCanDeleteACategory()
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $this->assertTrue($admin->can('delete', $category));
        $this->assertFalse($user->can('delete', $category));
    }
}
