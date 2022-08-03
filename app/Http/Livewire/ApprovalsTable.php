<?php

namespace App\Http\Livewire;

use App\States\Approved;
use App\States\Rejected;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApprovalsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Booking::class;

    protected $relationships = ['resource', 'user'];

    protected $editFields = [];

    public $tableDefaultSort = 'start_time';

    public $tableHeaders = [
        'resource_name' => 'Resource',
        'user_name' => 'User',
        'start_time' => 'Start',
        'end_time' => 'End',
        'status' => 'Status',
    ];

    protected $listeners = [
        'approve' => 'onApprove',
        'reject' => 'onReject',
    ];

    public function onApprove($id, $multiple = false)
    {
        $itemsToApprove = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->findOrFail($itemsToApprove);

        if ($this->canTransitionTo($items, Approved::class)) {
            $items
                ->each(function ($item) {
                    $this->authorize('approve', $item);
                })
                ->each->approve();

            if ($items->count() === 1) {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Booking approved',
                    'body' => 'The booking was approved.',
                    'level' => 'success',
                ]);
            } else {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Bookings approved',
                    'body' => $items->count().' bookings were approved.',
                    'level' => 'success',
                ]);
            }
        } else {
            if ($items->count() === 1) {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Booking not approved',
                    'body' => 'The booking could not be approved.',
                    'level' => 'error',
                ]);
            } else {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Bookings not approved',
                    'body' => 'One or more of the selected bookings could not be approved.',
                    'level' => 'error',
                ]);
            }
        }

        $this->refreshItems($itemsToApprove);
    }

    public function onReject($id, $multiple = false)
    {
        $itemsToReject = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->findOrFail($itemsToReject);

        if ($this->canTransitionTo($items, Rejected::class)) {
            $items
                ->each(function ($item) {
                    $this->authorize('approve', $item);
                })
                ->each->reject();

            if ($items->count() === 1) {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Booking rejected',
                    'body' => 'The booking was rejected.',
                    'level' => 'success',
                ]);
            } else {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Bookings rejected',
                    'body' => $items->count().' bookings were rejected.',
                    'level' => 'success',
                ]);
            }
        } else {
            if ($items->count() === 1) {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Booking not rejected',
                    'body' => 'The booking could not be rejected.',
                    'level' => 'error',
                ]);
            } else {
                $this->dispatchBrowserEvent('notify', [
                    'title' => 'Bookings not rejected',
                    'body' => 'One or more of the selected bookings could not be rejected.',
                    'level' => 'error',
                ]);
            }
        }

        $this->refreshItems($itemsToReject);
    }

    private function canTransitionTo(Collection $items, $state)
    {
        return $items->reduce(function ($carry, $item) use ($state) {
            return $carry && $item->state->canTransitionTo($state);
        }, true);
    }

    public function render()
    {
        return view('livewire.approvals-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
