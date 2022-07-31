<?php

namespace Tests\Feature\Tables;

use App\Http\Livewire\ApprovalsTable;
use App\Models\Booking;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ApprovalsTableTest extends TestCase
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

        Livewire::test(ApprovalsTable::class, ['items' => $items])
                ->assertSee([
                    $items[0]->name,
                    $items[1]->name,
                    $items[2]->name,
                ]);
    }

    /**
     * Administrator can approve a booking.
     *
     * @return void
     */
    public function testAdministratorCanApproveBooking()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $items = Booking::factory()
                        ->count(1)
                        ->for(User::factory()->hasAttached($group))
                        ->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ApprovalsTable::class, ['items' => $items])
                ->emit('approve', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isApproved);
    }

    /**
     * Group approver can approve a booking.
     *
     * @return void
     */
    public function testGroupApproverCanApproveBooking()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $items = Booking::factory()
                        ->count(1)
                        ->for(User::factory()->hasAttached($group))
                        ->create();

        Livewire::actingAs($approver)
                ->test(ApprovalsTable::class, ['items' => $items])
                ->emit('approve', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isApproved);
    }

    /**
     * Regular user can not approve a booking.
     *
     * @return void
     */
    public function testUserCanNotApproveBooking()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $items = Booking::factory()
                        ->count(1)
                        ->for(User::factory()->hasAttached($group))
                        ->create();

        Livewire::actingAs(User::factory()->create())
                ->test(ApprovalsTable::class, ['items' => $items])
                ->emit('approve', $items[0]->id)
                ->assertForbidden();

        $this->assertFalse($items[0]->fresh()->isApproved);
    }

    /**
     * Administrator can reject a booking.
     *
     * @return void
     */
    public function testAdministratorCanRejectBooking()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $items = Booking::factory()
                        ->count(1)
                        ->for(User::factory()->hasAttached($group))
                        ->create();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ApprovalsTable::class, ['items' => $items])
                ->emit('reject', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isRejected);
    }

    /**
     * Group approver can reject a booking.
     *
     * @return void
     */
    public function testGroupApproverCanRejectBooking()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $items = Booking::factory()
                        ->count(1)
                        ->for(User::factory()->hasAttached($group))
                        ->create();

        Livewire::actingAs($approver)
                ->test(ApprovalsTable::class, ['items' => $items])
                ->emit('reject', $items[0]->id)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'success';
                });

        $this->assertTrue($items[0]->fresh()->isRejected);
    }

    /**
     * Regular user can not reject a booking.
     *
     * @return void
     */
    public function testUserCanNotRejectBooking()
    {
        $this->approvalIsRequired();

        $approver = User::factory()->create();

        $group = Group::factory()->hasAttached($approver, [], 'approvers')->create();

        $items = Booking::factory()
                        ->count(1)
                        ->for(User::factory()->hasAttached($group))
                        ->create();

        Livewire::actingAs(User::factory()->create())
                ->test(ApprovalsTable::class, ['items' => $items])
                ->emit('reject', $items[0]->id)
                ->assertForbidden();

        $this->assertFalse($items[0]->fresh()->isRejected);
    }

    /**
     * Booking must have a valid state to be approved.
     *
     * @return void
     */
    public function testBookingMustHaveValidStateToBeApproved()
    {
        $this->approvalIsRequired();

        $items = Booking::factory()->current()->checkedout()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ApprovalsTable::class, ['items' => $items])
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
     *
     * @return void
     */
    public function testBookingMustHaveValidStateToBeRejected()
    {
        $this->approvalIsRequired();

        $items = Booking::factory()->current()->checkedout()->count(1)->createQuietly();

        Livewire::actingAs(User::factory()->admin()->create())
                ->test(ApprovalsTable::class, ['items' => $items])
                ->set('selectedRows', [$items[0]->id])
                ->emit('reject', null, true)
                ->assertOk()
                ->assertDispatchedBrowserEvent('notify', function ($name, $data) {
                    return data_get($data, 'level') === 'error';
                });

        $this->assertFalse($items[0]->fresh()->isRejected);
    }
}
