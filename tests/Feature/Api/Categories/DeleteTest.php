<?php

namespace Tests\Feature\Api\Categories;

use Hydrofon\Category;
use Hydrofon\User;
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
    public function testCategoriesCanBeDeleted()
    {
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->deleteJson('api/categories/'.$category->id)
             ->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
