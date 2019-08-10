<?php

namespace Tests\Feature\Categories;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories can be deleted.
     *
     * @return void
     */
    public function testCategoriesCanBeDeleted()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->delete('categories/'.$category->id);

        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', [
            'name' => $category->name,
        ]);
    }
}
