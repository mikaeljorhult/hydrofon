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
     * Posts request to persist a category.
     *
     * @param array               $overrides
     * @param \Hydrofon\User|null $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function storeCategory($overrides = [], $user = null)
    {
        $category = factory(Category::class)->make($overrides);

        return $this->actingAs($user ?: factory(User::class)->states('admin')->create())
                    ->post('categories', $category->toArray());
    }

    /**
     * Categories can be created and stored.
     *
     * @return void
     */
    public function testCategoriesCanBeStored()
    {
        $parent = factory(Category::class)->create();

        $this->storeCategory([
            'name' => 'New Category',
            'parent_id' => $parent->id,
        ])
             ->assertRedirect('/categories');

        $this->assertDatabaseHas('categories', [
            'name' => 'New Category',
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
        $this->storeCategory(['name' => null])
             ->assertRedirect()
             ->assertSessionHasErrors('name');

        $this->assertCount(0, Category::all());
    }

    /**
     * A parent category must exist in the database.
     *
     * @return void
     */
    public function testParentMustExist()
    {
        $this->storeCategory(['parent_id' => 100])
             ->assertRedirect()
             ->assertSessionHasErrors('parent_id');

        $this->assertCount(0, Category::all());
    }
}
