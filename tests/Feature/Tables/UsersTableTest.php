<?php

namespace Tests\Feature\Tables;

use App\Livewire\UsersTable;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UsersTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Table is rendered with items.
     */
    public function testItemsAreRendered(): void
    {
        $items = User::factory()->count(3)->create();

        Livewire::test(UsersTable::class, ['items' => $items])
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
        $items = User::factory()->count(1)->create();

        Livewire::test(UsersTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->assertSet('isEditing', $items[0]->id)
            ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited user can be saved.
     */
    public function testAdministratorCanEditAUser(): void
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(UsersTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.name', 'Updated User')
            ->dispatch('save')
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertDatabaseHas(User::class, [
            'name' => 'Updated User',
        ]);
    }

    /**
     * Regular user can not update a user.
     */
    public function testUserCanNotEditAUser(): void
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
            ->test(UsersTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.name', 'Updated User')
            ->dispatch('save')
            ->assertForbidden();

        $this->assertDatabaseHas(User::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Group relationships are stored.
     */
    public function testRelatedGroupsAreSaved(): void
    {
        $items = User::factory()->count(1)->create();
        $group = Group::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(UsersTable::class, ['items' => $items])
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
    public function testMissingGroupsAreNotAllowed(): void
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(UsersTable::class, ['items' => $items])
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
     * A user must have a name and an e-mail address.
     */
    public function testUserMustHaveANameAndEmail(): void
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(UsersTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.name', '')
            ->set('editValues.email', '')
            ->dispatch('save')
            ->assertHasErrors([
                'editValues.name',
                'editValues.email',
            ])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertDatabaseHas(User::class, [
            'name' => $items[0]->name,
            'email' => $items[0]->email,
        ]);
    }

    /**
     * User can be deleted.
     */
    public function testAdministratorCanDeleteUser(): void
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(UsersTable::class, ['items' => $items])
            ->dispatch('delete', $items[0]->id)
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertModelMissing($items[0]);
    }

    /**
     * An administrator can not delete its own account.
     */
    public function testAdministratorCanDeleteOwnAccount(): void
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs($items[0])
            ->test(UsersTable::class, ['items' => $items])
            ->dispatch('delete', $items[0]->id)
            ->assertForbidden();

        $this->assertModelExists($items[0]);
    }

    /**
     * Regular user can not delete user.
     */
    public function testUserCanNotDeleteUser(): void
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
            ->test(UsersTable::class, ['items' => $items])
            ->dispatch('delete', $items[0]->id)
            ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
