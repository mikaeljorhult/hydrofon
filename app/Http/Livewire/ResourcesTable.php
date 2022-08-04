<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ResourcesTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Resource::class;

    protected $relationships = ['buckets', 'categories', 'groups'];

    protected $editFields = [
        'id' => 'ID',
        'name' => 'name',
        'description' => 'description',
        'is_facility' => 'facility',
    ];

    public $tableHeaders = [
        'name' => 'Name',
        'description' => 'Description',
        'is_facility' => 'Facility',
    ];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatchBrowserEvent('notify', [
                        'title' => 'Resource could not be updated',
                        'body' => $validator->errors()->first(),
                        'level' => 'error',
                    ]);
                }
            });
        })->validate([
            'editValues.name' => ['required', 'max:60'],
            'editValues.description' => ['nullable'],
            'editValues.is_facility' => ['nullable'],
            'editValues.buckets' => ['nullable', 'array'],
            'editValues.buckets.*' => [Rule::exists('buckets', 'id')],
            'editValues.categories' => ['nullable', 'array'],
            'editValues.categories.*' => [Rule::exists('categories', 'id')],
            'editValues.groups' => ['nullable', 'array'],
            'editValues.groups.*' => [Rule::exists('groups', 'id')],
        ])['editValues'];

        $this->syncRelationship($item, $validated, 'buckets');
        $this->syncRelationship($item, $validated, 'categories');
        $this->syncRelationship($item, $validated, 'groups');
        $item->update($validated);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;

        $this->dispatchBrowserEvent('notify', [
            'title' => 'Resource was updated',
            'body' => 'The resource was updated successfully.',
            'level' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.resources-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
