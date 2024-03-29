<?php

namespace Tests\Feature\Resources;

use App\Models\Category;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Category relationships are stored when creating resource.
     */
    public function testCategoryRelationshipsAreStoredWhenCreatingResource(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $resource = Resource::factory()->make();

        $this->actingAs($admin)->post('resources', [
            'name' => $resource->name,
            'categories' => [$category->id],
        ]);

        $this->assertDatabaseHas('category_resource', [
            'resource_id' => 1,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Non-existing categories cannot be added when creating resource.
     */
    public function testNonExistingCategoriesCannotBeAddedWhenStoringResource(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->make();

        $response = $this->actingAs($admin)->post('resources', [
            'name' => $resource->name,
            'categories' => [100],
        ]);

        $response->assertSessionHasErrors('categories.*');
        $this->assertDatabaseMissing('category_resource', [
            'category_id' => 100,
        ]);
    }

    /**
     * Category relationships are stored when updating resource.
     */
    public function testCategoryRelationshipsAreStoredWhenUpdatingResource(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $resource = Resource::factory()->create();

        $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name' => 'New Resource Name',
            'categories' => [$category->id],
        ]);

        $this->assertDatabaseHas('category_resource', [
            'resource_id' => $resource->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Category relationships are removed when updating user.
     */
    public function testCategoryRelationshipsAreRemovedWhenUpdatingResource(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $resource = Resource::factory()->create();

        $resource->categories()->attach($category);

        $this->assertDatabaseHas('category_resource', [
            'resource_id' => $resource->id,
            'category_id' => $category->id,
        ]);

        $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name' => 'New Resource Name',
            'categories' => [],
        ]);

        $this->assertDatabaseMissing('category_resource', [
            'resource_id' => $resource->id,
            'category_id' => $category->id,
        ]);
    }

    /**
     * Non-existing categories cannot be added when updating resource.
     */
    public function testNonExistingCategoriesCannotBeAddedWhenUpdatingResource(): void
    {
        $admin = User::factory()->admin()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($admin)->put('resources/'.$resource->id, [
            'name' => 'New Resource Name',
            'categories' => [100],
        ]);

        $response->assertSessionHasErrors('categories.*');
        $this->assertDatabaseMissing('category_resource', [
            'category_id' => 100,
        ]);
    }
}
