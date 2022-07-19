<?php

namespace Tests\Feature\Tables;

use App\Http\Livewire\ProfileBookingsTable;
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
     *
     * @return void
     */
    public function testItemsAreRendered()
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
     *
     * @return void
     */
    public function testEditFormIsDisplayed()
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->assertSet('isEditing', $items[0]->id)
                ->assertSeeHtml('name="resource_id"');
    }

    /**
     * Edited booking can be saved.
     *
     * @return void
     */
    public function testAdministratorCanEditABooking()
    {
        $items = Booking::factory()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', $resource->id)
                ->emit('save')
                ->assertOk();

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Regular user can not update another users booking.
     *
     * @return void
     */
    public function testUserCanNotEditAnotherUsersBooking()
    {
        $items = Booking::factory()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs(User::factory()->create())
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', $resource->id)
                ->emit('save')
                ->assertForbidden();

        $this->assertDatabaseMissing(Booking::class, [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Regular user can update their own booking.
     *
     * @return void
     */
    public function testUserCanEditTheirOwnBooking()
    {
        $items = Booking::factory()->future()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs($items[0]->user)
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', $resource->id)
                ->emit('save')
                ->assertOk();

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $resource->id,
        ]);
    }

    /**
     * Regular user can not change user of their own booking.
     *
     * @return void
     */
    public function testUserCanNotChangeUserOfTheirOwnBooking()
    {
        $items = Booking::factory()->future()->count(1)->create();
        $user = User::factory()->create();

        Livewire::actingAs($items[0]->user)
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.user_id', $user->id)
                ->emit('save')
                ->assertHasErrors(['editValues.user_id']);

        $this->assertDatabaseMissing(Booking::class, [
            'user_id' => $user->id,
        ]);
    }

    /**
     * A booking have required attributes.
     *
     * @return void
     */
    public function testBookingHaveRequiredAttributes()
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', '')
                ->set('editValues.start_time', '')
                ->set('editValues.end_time', '')
                ->emit('save')
                ->assertHasErrors([
                    'editValues.resource_id',
                    'editValues.start_time',
                    'editValues.end_time',
                ]);

        $items[0] = $items[0]->fresh();

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $items[0]->resource_id,
            'start_time' => $items[0]->start_time,
            'end_time' => $items[0]->end_time,
        ]);
    }

    /**
     * Resource must exist to be allowed.
     *
     * @return void
     */
    public function testMissingResourceIsNotAllowed()
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', 100)
                ->emit('save')
                ->assertHasErrors(['editValues.resource_id']);

        $this->assertDatabaseHas(Booking::class, [
            'resource_id' => $items[0]->resource_id,
        ]);
    }

    /**
     * User must exist to be allowed.
     *
     * @return void
     */
    public function testMissingUserIsNotAllowed()
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.user_id', 100)
                ->emit('save')
                ->assertHasErrors(['editValues.user_id']);

        $this->assertDatabaseHas(Booking::class, [
            'user_id' => $items[0]->user_id,
        ]);
    }

    /**
     * Booking can be deleted.
     *
     * @return void
     */
    public function testAdministratorCanDeleteBooking()
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertOk();

        $this->assertModelMissing($items[0]);
    }

    /**
     * Regular user can not delete booking.
     *
     * @return void
     */
    public function testUserCanNotDeleteBooking()
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->create())
                ->test(ProfileBookingsTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
