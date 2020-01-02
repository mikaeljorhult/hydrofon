<?php

namespace Hydrofon\Http\Livewire;

use Hydrofon\Rules\Available;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class BookingsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Booking::class;
    protected $relationships = ['checkin', 'checkout', 'resource', 'user'];
    protected $editFields = ['id', 'resource_id', 'user_id', 'start_time', 'end_time'];

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
        ]);

        $item->update($validatedData['editValues']);

        $this->isEditing = false;
    }

    public function onCheckin($id, $multiple)
    {
        $itemsToCheckin = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->with('checkin')->findOrFail($itemsToCheckin);

        $items->each(function ($item, $key) {
            if (!$item->checkin) {
                $item->checkin()->create([
                    'user_id' => auth()->id(),
                ]);

                // Shorten booking if it has not ended yet.
                if ($item->end_time->isFuture() && $item->start_time->isPast()) {
                    $item->update([
                        'end_time' => now(),
                    ]);
                }
            }
        });
    }

    public function onCheckout($id, $multiple)
    {
        $itemsToCheckout = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->with('checkout')->findOrFail($itemsToCheckout);

        $items->each(function ($item, $key) {
            if (!$item->checkout) {
                $item->checkout()->create([
                    'user_id' => auth()->id(),
                ]);
            }
        });
    }

    public function onSwitch($id)
    {
        $item = $this->modelInstance->with('resource')->findOrFail($id);

        // Get buckets with available resources.
        $buckets = $item->resource->buckets()->with(['resources' => function ($query) use ($item) {
            $query->whereDoesntHave('bookings', function ($subQuery) use ($item) {
                $subQuery->between($item->start_time, $item->end_time);
            });
        }])->get();

        $availableResources = $buckets->pluck('resources')->flatten();

        $item->resource()->associate($availableResources->first());
        $item->save();
    }

    public function render()
    {
        return view('livewire.bookings-table', [
            'items' => $this->items,
        ]);
    }
}
