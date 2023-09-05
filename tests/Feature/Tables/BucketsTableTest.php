<?php

namespace Tests\Feature\Tables;

use App\Livewire\BucketsTable;
use App\Models\Bucket;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BucketsTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Table is rendered with items.
     */
    public function testItemsAreRendered(): void
    {
        $items = Bucket::factory()->count(3)->create();

        Livewire::test(BucketsTable::class, ['items' => $items])
                ->assertSee([
                    $items[0]->name,
                    $items[1]->name,
                    $items[2]->name,
                ]);
    }

    /**
     * Inline edit form is displayed.
     */
    public function testEditFormIsDisplayed(): void
    {
        $items = Bucket::factory()->count(1)->create();

        Livewire::test(BucketsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->assertSet('isEditing', $items[0]->id)
                ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited bucket can be saved.
     */
    public function testAdministratorCanEditABucket(): void
    {
        $items = Bucket::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BucketsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Bucket')
                ->dispatch('save')
                ->assertOk()
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertDatabaseHas(Bucket::class, [
            'name' => 'Updated Bucket',
        ]);
    }

    /**
     * Regular user can not update a bucket.
     */
    public function testUserCanNotEditABucket(): void
    {
        $items = Bucket::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(BucketsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Bucket')
                ->dispatch('save')
                ->assertForbidden();

        $this->assertDatabaseHas(Bucket::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Resource relationships are stored.
     */
    public function testRelatedResourcesAreSaved(): void
    {
        $items = Bucket::factory()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BucketsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.resources', [$resource->id])
                ->dispatch('save')
                ->assertOk();

        $this->assertEquals(1, $items[0]->resources()->count());
    }

    /**
     * A bucket must have a name.
     */
    public function testBucketMustHaveAName(): void
    {
        $items = Bucket::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BucketsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.name', '')
                ->dispatch('save')
                ->assertHasErrors(['editValues.name'])
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseHas(Bucket::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Resources must exist to be allowed.
     */
    public function testMissingResourcesAreNotAllowed(): void
    {
        $items = Bucket::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BucketsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.resources', [100])
                ->dispatch('save')
                ->assertHasErrors(['editValues.resources.*'])
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertEquals(0, $items[0]->resources()->count());
    }

    /**
     * Bucket can be deleted.
     */
    public function testAdministratorCanDeleteBucket(): void
    {
        $items = Bucket::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BucketsTable::class, ['items' => $items])
                ->dispatch('delete', $items[0]->id)
                ->assertOk()
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete bucket.
     */
    public function testUserCanNotDeleteBucket(): void
    {
        $items = Bucket::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(BucketsTable::class, ['items' => $items])
                ->dispatch('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
