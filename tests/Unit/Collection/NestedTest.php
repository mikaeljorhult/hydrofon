<?php

namespace Tests\Unit\Collection;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NestedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Nested macro return collection items.
     *
     * @return void
     */
    public function testItemsInCollectionAreIncluded()
    {
        $collection = collect([factory(Category::class)->make()]);
        $returned = $collection->nested('categories');

        $this->assertCount(1, $returned);
        $this->assertContains($collection->get(0), $returned);
    }

    /**
     * Nested macro return nested categories.
     *
     * @return void
     */
    public function testNestedCategoriesCanBeRetrievedFromCollection()
    {
        $this->actingAs(factory(User::class)->states('admin')->create());

        $parent = factory(Category::class)->create();
        $parent->categories()->create(factory(Category::class)->make()->toArray());

        $collection = collect([$parent]);
        $returned = $collection->nested('categories');

        $this->assertCount(2, $returned);
    }

    /**
     * Nested macro return nested resources within.
     *
     * @return void
     */
    public function testResourcesWithinCategoriesCanBeRetrievedFromCollection()
    {
        $this->actingAs(factory(User::class)->states('admin')->create());

        $parent = factory(Category::class)->create();
        $includedResource = factory(Resource::class)->create();
        $parent->resources()->attach($includedResource);

        $collection = collect([$parent]);
        $returned = $collection->nested('resources', 'categories', false);

        $this->assertCount(1, $returned);
        $this->assertEquals($includedResource->id, $returned->get(0)->id);
    }
}
