<?php

namespace Tests\Feature\Api\Categories;

use Hydrofon\Bucket;
use Hydrofon\Category;
use Hydrofon\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories can be created and stored.
     *
     * @return void
     */
    public function testCategoriesCanBeStored()
    {
        $category = factory(Bucket::class)->make();

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/categories', $category->toArray(), ['ACCEPT' => 'application/json']);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'id',
                     'name',
                 ])
                 ->assertJsonFragment([
                     'name'  => $category->name,
                 ]);

        $this->assertDatabaseHas('categories', [
            'id'    => 1,
            'name'  => $category->name,
        ]);
    }

    /**
     * Categories must have a name.
     *
     * @return void
     */
    public function testCategoriesMustHaveAName()
    {
        $category = factory(Category::class)->make(['name' => null]);

        $response = $this->actingAs(factory(User::class)->states('admin')->create())
                         ->post('api/categories', $category->toArray(), ['ACCEPT' => 'application/json']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('name');

        $this->assertEquals(0, Category::count());
    }
}
