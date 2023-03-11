<?php

namespace Tests\Feature\Tables;

use App\Http\Livewire\ResourcesTable;
use App\Models\Bucket;
use App\Models\Category;
use App\Models\Group;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResourcesTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Table is rendered with items.
     *
     * @return void
     */
    public function testItemsAreRendered(): void
    {
        $items = Resource::factory()->count(3)->create();

        Livewire::test(ResourcesTable::class, ['items' => $items])
                ->assertSee([
                    $items[0]->name,
                    $items[1]->name,
                    $items[2]->name,
                ]);
    }

    /**
     * Inline edit form is displayed.
     *
     * @return void
     */
    public function testEditFormIsDisplayed(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->assertSet('isEditing', $items[0]->id)
                ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited resource can be saved.
     *
     * @return void
     */
    public function testAdministratorCanEditAResource(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Resource')
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertDatabaseHas(Resource::class, [
            'name' => 'Updated Resource',
        ]);
    }

    /**
     * Regular user can not update a resource.
     *
     * @return void
     */
    public function testUserCanNotEditAResource(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Resource')
                ->emit('save')
                ->assertForbidden();

        $this->assertDatabaseHas(Resource::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Bucket relationships are stored.
     *
     * @return void
     */
    public function testRelatedBucketsAreSaved(): void
    {
        $items = Resource::factory()->count(1)->create();
        $bucket = Bucket::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.buckets', [$bucket->id])
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertEquals(1, $items[0]->buckets()->count());
    }

    /**
     * Category relationships are stored.
     *
     * @return void
     */
    public function testRelatedCategoriesAreSaved(): void
    {
        $items = Resource::factory()->count(1)->create();
        $category = Category::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.categories', [$category->id])
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertEquals(1, $items[0]->categories()->count());
    }

    /**
     * Group relationships are stored.
     *
     * @return void
     */
    public function testRelatedGroupsAreSaved(): void
    {
        $items = Resource::factory()->count(1)->create();
        $group = Group::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.groups', [$group->id])
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertEquals(1, $items[0]->groups()->count());
    }

    /**
     * A resource must have a name.
     *
     * @return void
     */
    public function testResourceMustHaveAName(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', '')
                ->emit('save')
                ->assertHasErrors(['editValues.name'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseHas(Resource::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Buckets must exist to be allowed.
     *
     * @return void
     */
    public function testMissingBucketsAreNotAllowed(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.buckets', [100])
                ->emit('save')
                ->assertHasErrors(['editValues.buckets.*'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertEquals(0, $items[0]->groups()->count());
    }

    /**
     * Categories must exist to be allowed.
     *
     * @return void
     */
    public function testMissingCategoriesAreNotAllowed(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.categories', [100])
                ->emit('save')
                ->assertHasErrors(['editValues.categories.*'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertEquals(0, $items[0]->groups()->count());
    }

    /**
     * Groups must exist to be allowed.
     *
     * @return void
     */
    public function testMissingGroupsAreNotAllowed(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.groups', [100])
                ->emit('save')
                ->assertHasErrors(['editValues.groups.*'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertEquals(0, $items[0]->groups()->count());
    }

    /**
     * Resource can be deleted.
     *
     * @return void
     */
    public function testAdministratorCanDeleteResource(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete resource.
     *
     * @return void
     */
    public function testUserCanNotDeleteResource(): void
    {
        $items = Resource::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(ResourcesTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
