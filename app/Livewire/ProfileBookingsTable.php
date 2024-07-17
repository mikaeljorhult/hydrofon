<?php

namespace App\Livewire;

use App\Enums\ApprovalSetting;
use App\Rules\Available;
use App\Settings\General;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProfileBookingsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Booking::class;

    protected $relationships = ['resource'];

    protected $editFields = [
        'id' => 'ID',
        'resource_id' => 'resource',
        'start_time' => 'start time',
        'end_time' => 'end time',
    ];

    public $tableDefaultSort = 'start_time';

    public $tableHeaders = [
        'resources.name' => 'Resource',
        'start_time' => 'Start',
        'end_time' => 'End',
    ];

    protected $listeners = [
        'edit' => 'onEdit',
        'save' => 'onSave',
        'delete' => 'onDelete',
    ];

    public function getHeadersProperty()
    {
        return app(General::class)->require_approval !== ApprovalSetting::NONE->value
            ? array_merge($this->tableHeaders, ['status' => 'Status'])
            : $this->tableHeaders;
    }

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

    public function render()
    {
        return view('livewire.profile-bookings-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
