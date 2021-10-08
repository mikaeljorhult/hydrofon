<?php

namespace App\Http\Livewire;

use App\Rules\Available;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class ProfileBookingsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Booking::class;
    protected $relationships = ['resource', 'statuses'];
    protected $editFields = ['id', 'resource_id', 'start_time', 'end_time'];

    public $tableDefaultSort = 'start_time';
    public $tableHeaders = [
        'resources.name' => 'Resource',
        'start_time'     => 'Start',
        'end_time'       => 'End',
        'status'         => 'Status',
    ];

    protected $listeners = [
        'select'    => 'onSelect',
        'selectAll' => 'onSelectAll',
        'edit'      => 'onEdit',
        'save'      => 'onSave',
        'delete'    => 'onDelete',
    ];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->editValues['start_time'], $this->editValues['end_time'], $item->id, 'resource_id'),
            ],
            'editValues.start_time'  => [
                'required', 'date', 'required_with:editValues.resource_id', 'before:editValues.end_time',
            ],
            'editValues.end_time'    => [
                'required', 'date', 'required_with:editValues.resource_id', 'after:editValues.start_time',
            ],
        ])['editValues'];

        $item->update($validatedData);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.profile-bookings-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
