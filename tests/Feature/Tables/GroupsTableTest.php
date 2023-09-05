<?php

namespace Tests\Feature\Tables;

use App\Livewire\GroupsTable;
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
     */
    public function testItemsAreRendered(): void
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
     */
    public function testEditFormIsDisplayed(): void
    {
        $items = Group::factory()->count(1)->create();

        Livewire::test(GroupsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->assertSet('isEditing', $items[0]->id)
                ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited group can be saved.
     */
    public function testAdministratorCanEditAGroup(): void
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Group')
                ->dispatch('save')
                ->assertOk()
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertDatabaseHas(Group::class, [
            'name' => 'Updated Group',
        ]);
    }

    /**
     * Regular user can not update a group.
     */
    public function testUserCanNotEditAGroup(): void
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.name', 'Updated Group')
                ->dispatch('save')
                ->assertForbidden();

        $this->assertDatabaseHas(Group::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Approver relationships are stored.
     */
    public function testRelatedApproversAreSaved(): void
    {
        $items = Group::factory()->count(1)->create();
        $approver = User::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.approvers', [$approver->id])
                ->dispatch('save')
                ->assertOk()
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertEquals(1, $items[0]->approvers()->count());
    }

    /**
     * A group must have a name.
     */
    public function testGroupMustHaveAName(): void
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.name', '')
                ->dispatch('save')
                ->assertHasErrors(['editValues.name'])
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseHas(Group::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Approvers must exist to be allowed.
     */
    public function testMissingApproversAreNotAllowed(): void
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->dispatch('edit', $items[0]->id)
                ->set('editValues.approvers', [100])
                ->dispatch('save')
                ->assertHasErrors(['editValues.approvers.*'])
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertEquals(0, $items[0]->approvers()->count());
    }

    /**
     * Group can be deleted.
     */
    public function testAdministratorCanDeleteGroup(): void
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->dispatch('delete', $items[0]->id)
                ->assertOk()
                ->assertDispatched('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete group.
     */
    public function testUserCanNotDeleteGroup(): void
    {
        $items = Group::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(GroupsTable::class, ['items' => $items])
                ->dispatch('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
