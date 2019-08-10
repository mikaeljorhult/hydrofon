<?php

namespace Tests\Feature\Resources;

use Hydrofon\User;
use Tests\TestCase;
use Hydrofon\Category;
use Hydrofon\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Category relationships are stored when creating resource.
     *
     * @return void
     */
    public function testCategoryRelationshipsAreStoredWhenCreatingResource()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();
        $resource = factory(Resource::class)->make();

        $this->actingAs($admin)->post('resources', [
            'name'       => $resource->name,
            'categories' => [$category->id],
        ]);

        $this->assertDatabaseHas('category_resource', [
            'resource_id' => 1,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Non-existing categories cannot be added when creating resource.
     *
     * @return void
     */
    public function testNonExistingCategoriesCannotBeAddedWhenStoringResource()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->make();

        $response = $this->actingAs($admin)->post('resources', [
            'name'       => $resource->name,
            'categories' => [100],
        ]);

        $response->assertSessionHasErrors('categories.*');
        $this->assertDatabaseMissing('category_resource', [
            'category_id' => 100,
        ]);
    }

    /**
     * Category relationships are stored when updating resource.
     *
     * @return void
     */
    public function testCategoryRelationshipsAreStoredWhenUpdatingResource()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();
        $resource = factory(Resource::class)->create();

        $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name'       => 'New Resource Name',
            'categories' => [$category->id],
        ]);

        $this->assertDatabaseHas('category_resource', [
            'resource_id' => $resource->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Category relationships are removed when updating user.
     *
     * @return void
     */
    public function testCategoryRelationshipsAreRemovedWhenUpdatingResource()
    {
        $admin = factory(User::class)->states('admin')->create();
        $category = factory(Category::class)->create();
        $resource = factory(Resource::class)->create();

        $resource->categories()->attach($category);

        $this->assertDatabaseHas('category_resource', [
            'resource_id' => $resource->id,
            'category_id' => $category->id,
        ]);

        $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name'       => 'New Resource Name',
            'categories' => [],
        ]);

        $this->assertDatabaseMissing('category_resource', [
            'resource_id' => $resource->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Non-existing categories cannot be added when updating resource.
     *
     * @return void
     */
    public function testNonExistingCategoriesCannotBeAddedWhenUpdatingResource()
    {
        $admin = factory(User::class)->states('admin')->create();
        $resource = factory(Resource::class)->create();

        $response = $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name'       => 'New Resource Name',
            'categories' => [100],
        ]);

        $response->assertSessionHasErrors('categories.*');
        $this->assertDatabaseMissing('category_resource', [
            'category_id' => 100,
        ]);
    }
}
