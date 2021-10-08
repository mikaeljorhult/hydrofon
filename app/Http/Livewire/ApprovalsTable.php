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
    ];

    protected $listeners = [
        'select'    => 'onSelect',
        'selectAll' => 'onSelectAll',
        'approve'   => 'onApprove',
    ];

    public function onApprove($id, $multiple = false)
    {
        $itemsToApprove = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->findOrFail($itemsToApprove);

        $items->each(function ($item, $key) {
            if (! $item->isApproved) {
                $item->approval()->create();
            }
        });

        $this->refreshItems($itemsToApprove);
    }

    public function render()
    {
        return view('livewire.approvals-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
