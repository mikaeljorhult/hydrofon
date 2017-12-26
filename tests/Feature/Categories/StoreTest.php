<?php

namespace Tests\Feature\Categories;

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
        $user     = factory(User::class)->create();
        $parent   = factory(Category::class)->create();
        $category = factory(Category::class)->make();

        $response = $this->actingAs($user)->post('categories', [
            'name'      => $category->name,
            'parent_id' => $parent->id,
        ]);

        $response->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', [
            'name'      => $category->name,
            'parent_id' => $parent->id,
        ]);
    }

    /**
     * Categories must have a name.
     *
     * @return void
     */
    public function testCategoriesMustHaveAName()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('categories', [
            'name' => '',
        ]);

        $response->assertRedirect();
        $this->assertCount(0, Category::all());
    }

    /**
     * A parent category must exist in the database.
     *
     * @return void
     */
    public function testParentMustExist()
    {
        $user     = factory(User::class)->create();
        $category = factory(Category::class)->make();

        $response = $this->actingAs($user)->post('categories', [
            'name'      => $category->name,
            'parent_id' => 100,
        ]);

        $response->assertRedirect();
        $this->assertCount(0, Category::all());
    }
}
