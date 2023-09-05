<?php

namespace App\Livewire;

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

    protected $editFields = [
        'id' => 'ID',
        'resource_id' => 'resource',
        'user_id' => 'user',
        'start_time' => 'start time',
        'end_time' => 'end time',
    ];

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
                    $this->dispatch('notify',
                        title: 'Booking could not be updated',
                        body: $validator->errors()->first(),
                        level: 'error',
                    );
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

        $this->dispatch('notify',
            title: 'Booking was updated',
            body: 'The booking was updated successfully.',
            level: 'success',
        );
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

            $this->refreshItems($itemsToCheckin);

            if ($items->count() === 1) {
                $this->dispatch('notify',
                    title: 'Booking was checked in',
                    body: 'The booking was checked in successfully.',
                    level: 'success',
                );
            } else {
                $this->dispatch('notify',
                    title: 'Bookings were checked in',
                    body: $items->count().' bookings were checked in successfully.',
                    level: 'success',
                );
            }
        } else {
            $this->dispatch('notify',
                title: 'Bookings not checked in',
                body: 'One or more of the selected bookings could not be checked in.',
                level: 'error',
            );
        }
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

            $this->refreshItems($itemsToCheckout);

            if ($items->count() === 1) {
                $this->dispatch('notify',
                    title: 'Booking was checked out',
                    body: 'The booking was checked out successfully.',
                    level: 'success',
                );
            } else {
                $this->dispatch('notify',
                    title: 'Bookings were checked out',
                    body: $items->count().' bookings were checked out successfully.',
                    level: 'success',
                );
            }
        } else {
            $this->dispatch('notify',
                title: 'Bookings not checked out',
                body: 'One or more of the selected bookings could not be checked out.',
                level: 'error',
            );
        }
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

            $this->refreshItems($itemsToApprove);

            if ($items->count() === 1) {
                $this->dispatch('notify',
                    title: 'Booking was approved',
                    body: 'The booking was approved successfully.',
                    level: 'success',
                );
            } else {
                $this->dispatch('notify',
                    title: 'Bookings were approved',
                    body: $items->count().' bookings were approved successfully.',
                    level: 'success',
                );
            }
        } else {
            $this->dispatch('notify',
                title: 'Bookings not approved',
                body: 'One or more of the selected bookings could not be approved.',
                level: 'error',
            );
        }
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

            $this->refreshItems($itemsToReject);

            if ($items->count() === 1) {
                $this->dispatch('notify',
                    title: 'Booking was rejected',
                    body: 'The booking was rejected successfully.',
                    level: 'success',
                );
            } else {
                $this->dispatch('notify',
                    title: 'Bookings were rejected',
                    body: $items->count().' bookings were rejected successfully.',
                    level: 'success',
                );
            }
        } else {
            $this->dispatch('notify',
                title: 'Bookings not rejected',
                body: 'One or more of the selected bookings could not be rejected.',
                level: 'error',
            );
        }
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

        if ($availableResources->isNotEmpty()) {
            $resource = $availableResources->first();
            $item->resource()->associate($resource);
            $item->save();

            $this->dispatch('notify',
                title: 'Booking was updated',
                body: 'The resource was changed to '.$resource->name.'.',
                level: 'success',
            );
        } else {
            $this->dispatch('notify',
                title: 'No available resources',
                body: 'There are no available resources during that time.',
                level: 'error',
            );
        }

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
