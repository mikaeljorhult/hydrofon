<?php

namespace App\Http\Livewire;

use App\Models\Identifier;
use App\Rules\Available;
use App\States\Approved;
use App\States\CheckedIn;
use App\States\CheckedOut;
use App\States\Rejected;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BookingsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Booking::class;

    protected $relationships = ['resource.buckets', 'user'];

    protected $editFields = ['id', 'resource_id', 'user_id', 'start_time', 'end_time'];

    public $tableDefaultSort = 'start_time';

    public $tableHeaders = [
        'resource_name' => 'Resource',
        'user_name' => 'User',
        'start_time' => 'Start',
        'end_time' => 'End',
        'state' => 'Status',
    ];

    protected $listeners = [
        'selectIdentifier' => 'onSelectIdentifier',
        'edit' => 'onEdit',
        'save' => 'onSave',
        'delete' => 'onDelete',
        'checkin' => 'onCheckin',
        'checkout' => 'onCheckout',
        'switch' => 'onSwitch',
        'approve' => 'onApprove',
        'reject' => 'onReject',
    ];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatchBrowserEvent('notify', [
                        'title' => 'Booking could not be updated',
                        'body' => $validator->errors()->first(),
                        'level' => 'error',
                    ]);
                }
            });
        })->validate([
            'editValues.user_id' => [
                'sometimes',
                'nullable',
                Rule::exists('users', 'id'),
                Rule::when(! auth()->user()->isAdmin(), [
                    Rule::in([auth()->id()]),
                ]),
            ],
            'editValues.resource_id' => [
                'required',
                Rule::exists('resources', 'id'),
                new Available($this->editValues['start_time'], $this->editValues['end_time'], $item->id, 'resource_id'),
            ],
            'editValues.start_time' => [
                'required', 'date', 'required_with:editValues.resource_id', 'before:editValues.end_time',
            ],
            'editValues.end_time' => [
                'required', 'date', 'required_with:editValues.resource_id', 'after:editValues.start_time',
            ],
        ])['editValues'];

        $item->update($validated);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;

        $this->dispatchBrowserEvent('notify', [
            'title' => 'Booking was updated',
            'body' => 'The booking was updated successfully.',
            'level' => 'success',
        ]);
    }

    public function onCheckin($id, $multiple = false)
    {
        $itemsToCheckin = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->with(['resource'])->findOrFail($itemsToCheckin);

        if ($this->canTransitionTo($items, CheckedIn::class)) {
            $items->each(function ($item) {
                if (! $item->resource->is_facility && ! $item->isCheckedIn) {
                    $item->state->transitionTo(CheckedIn::class);
                }
            });
        }

        $this->refreshItems($itemsToCheckin);
    }

    public function onCheckout($id, $multiple = false)
    {
        $itemsToCheckout = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->with(['resource'])->findOrFail($itemsToCheckout);

        if ($this->canTransitionTo($items, CheckedOut::class)) {
            $items->each(function ($item) {
                if (! $item->resource->is_facility && ! $item->isCheckedOut) {
                    $item->state->transitionTo(CheckedOut::class);
                }
            });
        }

        $this->refreshItems($itemsToCheckout);
    }

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

    public function onSelectIdentifier($identifier)
    {
        $resourceId = Identifier::where('value', $identifier)->soleValue('identifiable_id');

        $bookings = $this->items->where('resource_id', $resourceId)->pluck('id');

        if ($bookings->count() > 0) {
            $this->onSelect($bookings->first(), true);
        }
    }

    private function canTransitionTo(Collection $items, $state)
    {
        return $items->reduce(function ($carry, $item) use ($state) {
            return $carry && $item->state->canTransitionTo($state);
        }, true);
    }

    public function render()
    {
        return view('livewire.bookings-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
