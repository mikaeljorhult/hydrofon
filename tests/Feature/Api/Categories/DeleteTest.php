<?php

namespace Tests\Feature\Api\Categories;

use Hydrofon\Bucket;
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
        $admin = factory(User::class)->states('admin')->create();
        $categories = factory(Category::class)->create();

        $response = $this->actingAs($admin)->delete('api/categories/'.$categories->id, ['ACCEPT' => 'application/json']);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', [
            'id' => $categories->id,
        ]);
    }
}
