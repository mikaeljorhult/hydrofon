<?php

namespace Tests\Feature\Tables;

use App\Livewire\CategoriesTable;
use App\Models\Category;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoriesTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Table is rendered with items.
     */
    public function testItemsAreRendered(): void
    {
        $items = Category::factory()->count(3)->create();

        Livewire::test(CategoriesTable::class, ['items' => $items])
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
        $items = Category::factory()->count(1)->create();

        Livewire::test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->assertSet('isEditing', $items[0]->id)
            ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited category can be saved.
     */
    public function testAdministratorCanEditACategory(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.name', 'Updated Category')
            ->dispatch('save')
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertDatabaseHas(Category::class, [
            'name' => 'Updated Category',
        ]);
    }

    /**
     * Regular user can not update a category.
     */
    public function testUserCanNotEditACategory(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.name', 'Updated Category')
            ->dispatch('save')
            ->assertForbidden();

        $this->assertDatabaseHas(Category::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Parent relationship is stored.
     */
    public function testParentRelationshipIsSaved(): void
    {
        $items = Category::factory()->count(1)->create();
        $parent = Category::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.parent_id', $parent->id)
            ->dispatch('save')
            ->assertHasNoErrors()
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertEquals(1, $items[0]->fresh()->parent()->count());
    }

    /**
     * Parent must exist to be allowed.
     */
    public function testMissingParentIsNotAllowed(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.parent_id', 100)
            ->dispatch('save')
            ->assertHasErrors(['editValues.parent_id'])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertEquals(0, $items[0]->fresh()->parent()->count());
    }

    /**
     * Category can't be its own parent.
     */
    public function testCategoryCanNotBeItsOwnParent(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.parent_id', $items[0]->id)
            ->dispatch('save')
            ->assertHasErrors(['editValues.parent_id'])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertEquals(0, $items[0]->fresh()->parent()->count());
    }

    /**
     * Group relationships are stored.
     */
    public function testRelatedGroupsAreSaved(): void
    {
        $items = Category::factory()->count(1)->create();
        $group = Group::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.groups', [$group->id])
            ->dispatch('save')
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertEquals(1, $items[0]->groups()->count());
    }

    /**
     * Resources must exist to be allowed.
     */
    public function testMissingResourcesAreNotAllowed(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.groups', [100])
            ->dispatch('save')
            ->assertHasErrors(['editValues.groups.*'])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertEquals(0, $items[0]->groups()->count());
    }

    /**
     * A category must have a name.
     */
    public function testCategoryMustHaveAName(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.name', '')
            ->dispatch('save')
            ->assertHasErrors(['editValues.name'])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertDatabaseHas(Category::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Category can be deleted.
     */
    public function testAdministratorCanDeleteCategory(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('delete', $items[0]->id)
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete category.
     */
    public function testUserCanNotDeleteCategory(): void
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
            ->test(CategoriesTable::class, ['items' => $items])
            ->dispatch('delete', $items[0]->id)
            ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
