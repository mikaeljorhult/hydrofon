<?php

namespace Tests\Feature\Api\Categories;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/categories/'.$category->id, [
                 'name' => 'Updated Name',
             ])
             ->assertStatus(202)
             ->assertJsonStructure([
                 'id',
                 'name',
             ])
             ->assertJsonFragment([
                 'id'   => $category->id,
                 'name' => 'Updated Name',
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
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->states('admin')->create())
             ->putJson('api/categories/'.$category->id, [
                 'name' => '',
             ])
             ->assertStatus(422)
             ->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
        ]);
    }
}
