<?php

namespace Tests\Feature\Tables;

use App\Http\Livewire\GroupsTable;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GroupsTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Table is rendered with items.
     *
     * @return void
     */
    public function testItemsAreRendered()
    {
        $items = Group::factory()->count(3)->create();

        Livewire::test(GroupsTable::class, ['items' => $items])
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
        $items = Group::factory()->count(1)->create();

        Livewire::test(GroupsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->assertSet('isEditing', $items[0]->id)
                ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited group can be saved.
     *
     * @return void
     */
    public function testAdministratorCanEditAGroup()
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Group')
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertDatabaseHas(Group::class, [
            'name' => 'Updated Group',
        ]);
    }

    /**
     * Regular user can not update a group.
     *
     * @return void
     */
    public function testUserCanNotEditAGroup()
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Group')
                ->emit('save')
                ->assertForbidden();

        $this->assertDatabaseHas(Group::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Approver relationships are stored.
     *
     * @return void
     */
    public function testRelatedApproversAreSaved()
    {
        $items = Group::factory()->count(1)->create();
        $approver = User::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.approvers', [$approver->id])
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertEquals(1, $items[0]->approvers()->count());
    }

    /**
     * A group must have a name.
     *
     * @return void
     */
    public function testGroupMustHaveAName()
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', '')
                ->emit('save')
                ->assertHasErrors(['editValues.name'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseHas(Group::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Approvers must exist to be allowed.
     *
     * @return void
     */
    public function testMissingApproversAreNotAllowed()
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.approvers', [100])
                ->emit('save')
                ->assertHasErrors(['editValues.approvers.*'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertEquals(0, $items[0]->approvers()->count());
    }

    /**
     * Group can be deleted.
     *
     * @return void
     */
    public function testAdministratorCanDeleteGroup()
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertOk();

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete group.
     *
     * @return void
     */
    public function testUserCanNotDeleteGroup()
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
