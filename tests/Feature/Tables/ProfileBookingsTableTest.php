<?php

namespace Tests\Feature\Tables;

use App\Livewire\ProfileBookingsTable;
use App\Models\Booking;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileBookingsTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Table is rendered with items.
     */
    public function testItemsAreRendered(): void
    {
        $items = Booking::factory()->count(3)->create();

        Livewire::test(ProfileBookingsTable::class, ['items' => $items])
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
        $items = Booking::factory()->count(1)->create();

        Livewire::test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->assertSet('isEditing', $items[0]->id)
            ->assertSeeHtml('name="resource_id"');
    }

    /**
     * Edited booking can be saved.
     */
    public function testAdministratorCanEditABooking(): void
    {
        $items = Booking::factory()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.resource_id', $resource->id)
            ->dispatch('save')
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Regular user can not update another users booking.
     */
    public function testUserCanNotEditAnotherUsersBooking(): void
    {
        $items = Booking::factory()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->create())
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.resource_id', $resource->id)
            ->dispatch('save')
            ->assertForbidden();

        $this->assertDatabaseMissing(Booking::class, [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Regular user can update their own booking.
     */
    public function testUserCanEditTheirOwnBooking(): void
    {
        $items = Booking::factory()->future()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs($items[0]->user)
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.resource_id', $resource->id)
            ->dispatch('save')
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Regular user can not change user of their own booking.
     */
    public function testUserCanNotChangeUserOfTheirOwnBooking(): void
    {
        $items = Booking::factory()->future()->count(1)->create();
        $user = User::factory()->create();

        Livewire::actingAs($items[0]->user)
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.user_id', $user->id)
            ->dispatch('save')
            ->assertHasErrors(['editValues.user_id'])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertDatabaseMissing(Booking::class, [
            'user_id' => $user->id,
        ]);
    }

    /**
     * A booking have required attributes.
     */
    public function testBookingHaveRequiredAttributes(): void
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.resource_id', '')
            ->set('editValues.start_time', '')
            ->set('editValues.end_time', '')
            ->dispatch('save')
            ->assertHasErrors([
                'editValues.resource_id',
                'editValues.start_time',
                'editValues.end_time',
            ])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $items[0] = $items[0]->fresh();

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $items[0]->resource_id,
            'start_time' => $items[0]->start_time,
            'end_time' => $items[0]->end_time,
        ]);
    }

    /**
     * Resource must exist to be allowed.
     */
    public function testMissingResourceIsNotAllowed(): void
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.resource_id', 100)
            ->dispatch('save')
            ->assertHasErrors(['editValues.resource_id'])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $items[0]->resource_id,
        ]);
    }

    /**
     * User must exist to be allowed.
     */
    public function testMissingUserIsNotAllowed(): void
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('edit', $items[0]->id)
            ->set('editValues.user_id', 100)
            ->dispatch('save')
            ->assertHasErrors(['editValues.user_id'])
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'error';
            });

        $this->assertDatabaseHas(Booking::class, [
            'user_id' => $items[0]->user_id,
        ]);
    }

    /**
     * Booking can be deleted.
     */
    public function testAdministratorCanDeleteBooking(): void
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('delete', $items[0]->id)
            ->assertOk()
            ->assertDispatched('notify', function ($name, $data) {
                return data_get($data, 'level') === 'success';
            });

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete booking.
     */
    public function testUserCanNotDeleteBooking(): void
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
            ->test(ProfileBookingsTable::class, ['items' => $items])
            ->dispatch('delete', $items[0]->id)
            ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
