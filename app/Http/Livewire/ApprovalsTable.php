<?php

namespace App\Http\Livewire;

use App\Rules\Available;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

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
        'select'    => 'onSelect',
        'selectAll' => 'onSelectAll',
        'approve'   => 'onApprove',
        'reject'    => 'onReject',
    ];

    public function onApprove($id, $multiple = false)
    {
        $itemsToApprove = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->findOrFail($itemsToApprove);

        $items->each(function ($item, $key) {
            if ($item->status !== 'approved') {
                $item->approval()->create();
                $item->setStatus('approved', 'Approved by '.auth()->user()->name);
            }
        });

        $this->refreshItems($itemsToApprove);
    }

    public function onReject($id, $multiple = false)
    {
        $itemsToReject = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->with(['approval'])->findOrFail($itemsToReject);

        $items->each(function ($item, $key) {
            if ($item->status !== 'rejected') {
                if ($item->approval) {
                    $item->approval()->delete();
                }

                $item->setStatus('rejected', 'Rejected by '.auth()->user()->name);
            }
        });

        $this->refreshItems($itemsToReject);
    }

    public function render()
    {
        return view('livewire.approvals-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
