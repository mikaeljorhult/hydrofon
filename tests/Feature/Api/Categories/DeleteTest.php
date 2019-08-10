<?php

namespace Tests\Feature\Api\Categories;

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
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->deleteJson('api/categories/'.$category->id)
             ->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
