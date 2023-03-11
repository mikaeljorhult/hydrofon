<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories can be deleted.
     *
     * @return void
     */
    public function testCategoriesCanBeDeleted(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->delete('categories/'.$category->id);

        $response->assertRedirect('/categories');
        $this->assertModelMissing($category);
    }
}
