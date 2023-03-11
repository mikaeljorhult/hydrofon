<?php

namespace Tests\Feature\Tables;

use App\Http\Livewire\BookingsTable;
use App\Models\Booking;
use App\Models\Bucket;
use App\Models\Group;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingsTableTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Table is rendered with items.
     */
    public function testItemsAreRendered(): void
    {
        $items = Booking::factory()->count(3)->create();

        Livewire::test(BookingsTable::class, ['items' => $items])
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

        Livewire::test(BookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
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
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', $resource->id)
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
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
                ->test(BookingsTable::class, ['items' => $items])
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
     */
    public function testUserCanEditTheirOwnBooking(): void
    {
        $items = Booking::factory()->future()->count(1)->create();
        $resource = Resource::factory()->create();

        Livewire::actingAs($items[0]->user)
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', $resource->id)
                ->emit('save')
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
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
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.user_id', $user->id)
                ->emit('save')
                ->assertHasErrors(['editValues.user_id'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
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
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', '')
                ->set('editValues.start_time', '')
                ->set('editValues.end_time', '')
                ->emit('save')
                ->assertHasErrors([
                    'editValues.resource_id',
                    'editValues.start_time',
                    'editValues.end_time',
                ])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
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
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.resource_id', 100)
                ->emit('save')
                ->assertHasErrors(['editValues.resource_id'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
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
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('edit', $items[0]->id)
                ->set('editValues.user_id', 100)
                ->emit('save')
                ->assertHasErrors(['editValues.user_id'])
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertDatabaseHas(Booking::class, [
            'user_id' => $items[0]->user_id,
        ]);
    }

    /**
     * Booking can be checked out.
     */
    public function testAdministratorCanCheckoutBooking(): void
    {
        $items = Booking::factory()->current()->approved()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('checkout', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isCheckedOut);
    }

    /**
     * Resource can be changed to other in same bucket.
     */
    public function testAdministratorCanSwitchResource(): void
    {
        $bucket = Bucket::factory()->hasResources(2)->create();

        $items = Booking::factory()
                        ->current()
                        ->approved()
                        ->count(1)
                        ->for($bucket->resources->first())
                        ->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('switch', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertEquals($items[0]->fresh()->resource_id, $bucket->resources->last()->id);
    }

    /**
     * Resource can not be switch if only one resource in bucket.
     */
    public function testBucketMustHaveMultipleResources(): void
    {
        $bucket = Bucket::factory()->hasResources(1)->create();

        $items = Booking::factory()
                        ->current()
                        ->approved()
                        ->count(1)
                        ->for($bucket->resources->first())
                        ->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('switch', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertEquals($items[0]->fresh()->resource_id, $bucket->resources->first()->id);
    }

    /**
     * Resource can not be switch if only one resource in bucket.
     */
    public function testResourceMustBeAvailableToSwitch(): void
    {
        $items = Booking::factory()
                        ->current()
                        ->approved()
                        ->count(2)
                        ->createQuietly();

        Bucket::factory()->hasAttached($items->pluck('resource'))->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('switch', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertNotEquals($items[0]->fresh()->resource_id, $items[1]->resource_id);
    }

    /**
     * Booking can be checked in.
     */
    public function testAdministratorCanCheckinBooking(): void
    {
        $items = Booking::factory()->current()->checkedout()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('checkin', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isCheckedIn);
    }

    /**
     * Booking can be approved.
     */
    public function testBookingCanBeApproved(): void
    {
        $this->approvalIsRequired();

        $group = Group::factory()
                      ->hasAttached(User::factory()->create(), [], 'approvers')
                      ->create();

        $items = Booking::factory()
                        ->current()
                        ->for(User::factory()->hasAttached($group))
                        ->count(1)
                        ->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('approve', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isApproved);
    }

    /**
     * Booking can be rejected.
     */
    public function testBookingCanBeRejected(): void
    {
        $this->approvalIsRequired();

        $group = Group::factory()
                      ->hasAttached(User::factory()->create(), [], 'approvers')
                      ->create();

        $items = Booking::factory()
                        ->current()
                        ->for(User::factory()->hasAttached($group))
                        ->count(1)
                        ->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('reject', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isRejected);
    }

    /**
     * Booking must have a valid state to be checked in.
     */
    public function testBookingMustHaveValidStateToBeCheckedIn(): void
    {
        $items = Booking::factory()->current()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->set('selectedRows', [$items[0]->id])
                ->emit('checkin', null, true)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertFalse($items[0]->fresh()->isCheckedIn);
    }

    /**
     * Booking must have a valid state to be checked out.
     */
    public function testBookingMustHaveValidStateToBeCheckedOut(): void
    {
        $items = Booking::factory()->current()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->set('selectedRows', [$items[0]->id])
                ->emit('checkout', null, true)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertFalse($items[0]->fresh()->isCheckedOut);
    }

    /**
     * Booking must have a valid state to be approved.
     */
    public function testBookingMustHaveValidStateToBeApproved(): void
    {
        $this->approvalIsRequired();

        $items = Booking::factory()->current()->checkedout()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->set('selectedRows', [$items[0]->id])
                ->emit('approve', null, true)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertFalse($items[0]->fresh()->isApproved);
    }

    /**
     * Booking must have a valid state to be rejected.
     */
    public function testBookingMustHaveValidStateToBeRejected(): void
    {
        $this->approvalIsRequired();

        $items = Booking::factory()->current()->checkedout()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->set('selectedRows', [$items[0]->id])
                ->emit('reject', null, true)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertFalse($items[0]->fresh()->isRejected);
    }

    /**
     * Booking can be deleted.
     */
    public function testAdministratorCanDeleteBooking(): void
    {
        $items = Booking::factory()->count(1)->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
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
                ->test(BookingsTable::class, ['items' => $items])
                ->emit('delete', $items[0]->id)
                ->assertForbidden();

        $this->assertModelExists($items[0]);
    }
}
