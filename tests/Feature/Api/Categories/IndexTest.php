<?php

namespace Tests\Feature\Api\Categories;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Categories are listed in index.
     *
     * @return void
     */
    public function testCategoriesAreListed()
    {
        $category = factory(Category::class)->create();

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/categories')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'name',
                     ],
                 ],
             ])
             ->assertJsonFragment([
                 'id'   => $category->id,
                 'name' => $category->name,
             ]);
    }

    /**
     * Categories can be filtered by name.
     *
     * @return void
     */
    public function testCategoriesAreFilteredByName()
    {
        $excludedCategory = factory(Category::class)->create(['name' => 'Excluded Category']);
        $includedCategory = factory(Category::class)->create(['name' => 'Included Category']);

        $this->actingAs(factory(User::class)->create())
             ->getJson('api/categories?filter[categories.name]=included')
             ->assertStatus(200)
             ->assertJsonFragment([
                 'id' => $includedCategory->id,
             ])
             ->assertJsonMissing([
                 'id' => $excludedCategory->id,
             ]);
    }
}
