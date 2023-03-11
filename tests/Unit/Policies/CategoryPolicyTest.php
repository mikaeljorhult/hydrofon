<?php

namespace Tests\Unit\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryPolicyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Only administrators can view a category.
     */
    public function testOnlyAdminUsersCanViewACategory(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $this->assertTrue($admin->can('view', $category));
        $this->assertFalse($user->can('view', $category));
    }

    /**
     * Only administrators can create categories.
     */
    public function testOnlyAdminUsersCanCreateCategories(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->assertTrue($admin->can('create', Category::class));
        $this->assertFalse($user->can('create', Category::class));
    }

    /**
     * Only administrators can update a category.
     */
    public function testOnlyAdminUsersCanUpdateACategory(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $this->assertTrue($admin->can('update', $category));
        $this->assertFalse($user->can('update', $category));
    }

    /**
     * Only administrators can delete a category.
     */
    public function testOnlyAdminUsersCanDeleteACategory(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $this->assertTrue($admin->can('delete', $category));
        $this->assertFalse($user->can('delete', $category));
    }
}
