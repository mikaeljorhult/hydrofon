<?php

namespace Tests\Feature\Tables;

use App\Http\Livewire\CategoriesTable;
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
     *
     * @return void
     */
    public function testItemsAreRendered()
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
     *
     * @return void
     */
    public function testEditFormIsDisplayed()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->assertSet('isEditing', $items[0]->id)
                ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited category can be saved.
     *
     * @return void
     */
    public function testAdministratorCanEditACategory()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Category')
                ->emit('save')
                ->assertOk();

        $this->assertDatabaseHas(Category::class, [
            'name' => 'Updated Category',
        ]);
    }

    /**
     * Regular user can not update a category.
     *
     * @return void
     */
    public function testUserCanNotEditACategory()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Category')
                ->emit('save')
                ->assertForbidden();

        $this->assertDatabaseHas(Category::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Parent relationship is stored.
     *
     * @return void
     */
    public function testParentRelationshipIsSaved()
    {
        $items  = Category::factory()->count(1)->create();
        $parent = Category::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.parent_id', $parent->id)
                ->emit('save')
                ->assertHasNoErrors()
                ->assertOk();

        $this->assertEquals(1, $items[0]->fresh()->parent()->count());
    }

    /**
     * Parent must exist to be allowed.
     *
     * @return void
     */
    public function testMissingParentIsNotAllowed()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.parent_id', 100)
                ->emit('save')
                ->assertHasErrors(['editValues.parent_id']);

        $this->assertEquals(0, $items[0]->fresh()->parent()->count());
    }

    /**
     * Category can't be its own parent.
     *
     * @return void
     */
    public function testCategoryCanNotBeItsOwnParent()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.parent_id', $items[0]->id)
                ->emit('save')
                ->assertHasErrors(['editValues.parent_id']);

        $this->assertEquals(0, $items[0]->fresh()->parent()->count());
    }

    /**
     * Group relationships are stored.
     *
     * @return void
     */
    public function testRelatedGroupsAreSaved()
    {
        $items = Category::factory()->count(1)->create();
        $group = Group::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.groups', [$group->id])
                ->emit('save')
                ->assertOk();

        $this->assertEquals(1, $items[0]->groups()->count());
    }

    /**
     * Resources must exist to be allowed.
     *
     * @return void
     */
    public function testMissingResourcesAreNotAllowed()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.groups', [100])
                ->emit('save')
                ->assertHasErrors(['editValues.groups.*']);

        $this->assertEquals(0, $items[0]->groups()->count());
    }

    /**
     * A category must have a name.
     *
     * @return void
     */
    public function testCategoryMustHaveAName()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', '')
                ->emit('save')
                ->assertHasErrors(['editValues.name']);

        $this->assertDatabaseHas(Category::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Category can be deleted.
     *
     * @return void
     */
    public function testAdministratorCanDeleteCategory()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertOk();

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete category.
     *
     * @return void
     */
    public function testUserCanNotDeleteCategory()
    {
        $items = Category::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(CategoriesTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
