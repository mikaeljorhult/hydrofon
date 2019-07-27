<?php

namespace Tests\Feature\Api\Categories;

use Hydrofon\Bucket;
use Hydrofon\Category;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories can be updated.
     *
     * @return void
     */
    public function testCategoriesCanBeUpdated()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->put('api/categories/'.$category->id, [
            'name'  => 'Updated Name',
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(202)
                 ->assertJsonStructure([
                     'id',
                     'name',
                 ])
                 ->assertJsonFragment([
                     'id'    => $category->id,
                     'name'  => 'Updated Name',
                 ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Categories must have a name.
     *
     * @return void
     */
    public function testCategoriesMustHaveName()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();

        $response = $this->actingAs($admin)->put('api/categories/'.$category->id, [
            'name'  => '',
        ], ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
        ]);
    }
}
