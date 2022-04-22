<?php

namespace Tests\Feature\Tables;

use App\Http\Livewire\UsersTable;
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
     *
     * @return void
     */
    public function testItemsAreRendered()
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
     *
     * @return void
     */
    public function testEditFormIsDisplayed()
    {
        $items = User::factory()->count(1)->create();

        Livewire::test(UsersTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->assertSet('isEditing', $items[0]->id)
                ->assertSeeHtml('name="name" value="'.$items[0]->name.'"');
    }

    /**
     * Edited user can be saved.
     *
     * @return void
     */
    public function testAdministratorCanEditAUser()
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(UsersTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated User')
                ->emit('save')
                ->assertOk();

        $this->assertDatabaseHas(User::class, [
            'name' => 'Updated User',
        ]);
    }

    /**
     * Regular user can not update a user.
     *
     * @return void
     */
    public function testUserCanNotEditAUser()
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(UsersTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', 'Updated User')
                ->emit('save')
                ->assertForbidden();

        $this->assertDatabaseHas(User::class, [
            'name' => $items[0]->name,
        ]);
    }

    /**
     * Group relationships are stored.
     *
     * @return void
     */
    public function testRelatedGroupsAreSaved()
    {
        $items = User::factory()->count(1)->create();
        $group = Group::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(UsersTable::class, ['items' => $items])
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
    public function testMissingGroupsAreNotAllowed()
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(UsersTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.groups', [100])
                ->emit('save')
                ->assertHasErrors(['editValues.groups.*']);

        $this->assertEquals(0, $items[0]->groups()->count());
    }

    /**
     * A user must have a name and an e-mail address.
     *
     * @return void
     */
    public function testUserMustHaveANameAndEmail()
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(UsersTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.name', '')
                ->set('editValues.email', '')
                ->emit('save')
                ->assertHasErrors([
                    'editValues.name',
                    'editValues.email',
                ]);

        $this->assertDatabaseHas(User::class, [
            'name'  => $items[0]->name,
            'email' => $items[0]->email,
        ]);
    }

    /**
     * User can be deleted.
     *
     * @return void
     */
    public function testAdministratorCanDeleteUser()
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(UsersTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertOk();

        $this->assertModelMissing($items[0]);
    }

    /**
     * An administrator can not delete its own account.
     *
     * @return void
     */
    public function testAdministratorCanDeleteOwnAccount()
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs($items[0])
                ->test(UsersTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }

    /**
     * Regular user can not delete user.
     *
     * @return void
     */
    public function testUserCanNotDeleteUser()
    {
        $items = User::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(UsersTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
