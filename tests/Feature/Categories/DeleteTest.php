<?php

namespace Tests\Feature\Categories;

use Hydrofon\Category;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories can be deleted.
     *
     * @return void
     */
    public function testCategoriesCanBeDeleted()
    {
        $user     = factory(User::class)->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($user)->delete('categories/' . $category->id);

        $response->assertRedirect('/categories');
        $this->assertDatabaseMissing('categories', [
            'name' => $category->name,
        ]);
    }
}
