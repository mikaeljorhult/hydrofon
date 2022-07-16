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
        'user_name'     => 'User',
        'start_time'    => 'Start',
        'end_time'      => 'End',
        'status'        => 'Status',
    ];

    protected $listeners = [
        'approve' => 'onApprove',
        'reject'  => 'onReject',
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
