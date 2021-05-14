<?php

namespace App\Http\Livewire;

use App\Rules\Available;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class BookingsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Booking::class;
    protected $relationships = ['checkin', 'checkout', 'resource.buckets', 'user'];
    protected $editFields = ['id', 'resource_id', 'user_id', 'start_time', 'end_time'];

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
        'edit'      => 'onEdit',
        'save'      => 'onSave',
        'delete'    => 'onDelete',
        'checkin'   => 'onCheckin',
        'checkout'  => 'onCheckout',
        'switch'    => 'onSwitch',
    ];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.user_id'     => ['sometimes', 'nullable', Rule::exists('users', 'id')],
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

    public function onCheckin($id, $multiple = false)
    {
        $itemsToCheckin = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->with(['resource', 'checkin'])->findOrFail($itemsToCheckin);

        $items->each(function ($item, $key) {
            if (! $item->resource->is_facility && ! $item->checkin) {
                $item->checkin()->create();

                // Shorten booking if it has not ended yet.
                if ($item->end_time->isFuture() && $item->start_time->isPast()) {
                    $item->update([
                        'end_time' => now(),
                    ]);
                }
            }
        });

        $this->refreshItems($itemsToCheckin);
    }

    public function onCheckout($id, $multiple = false)
    {
        $itemsToCheckout = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->with(['resource', 'checkout'])->findOrFail($itemsToCheckout);

        $items->each(function ($item, $key) {
            if (! $item->resource->is_facility && ! $item->checkout) {
                $item->checkout()->create();
            }
        });

        $this->refreshItems($itemsToCheckout);
    }

    public function onSwitch($id)
    {
        $item = $this->modelInstance->with('resource')->findOrFail($id);

        // Get buckets with available resources.
        $buckets = $item->resource->buckets()->with([
            'resources' => function ($query) use ($item) {
                $query->whereDoesntHave('bookings', function ($subQuery) use ($item) {
                    $subQuery->between($item->start_time, $item->end_time);
                });
            },
        ])->get();

        $availableResources = $buckets->pluck('resources')->flatten();

        $item->resource()->associate($availableResources->first());
        $item->save();

        $this->refreshItems([$id]);
    }

    public function render()
    {
        return view('livewire.bookings-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
