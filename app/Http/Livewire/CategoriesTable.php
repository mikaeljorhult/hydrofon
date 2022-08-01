<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CategoriesTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Category::class;

    protected $relationships = ['parent', 'groups'];

    protected $editFields = ['id', 'name', 'parent_id'];

    public $tableDefaultSort = 'name';

    public $tableHeaders = [
        'name' => 'Name',
        'parent_name' => 'Parent',
    ];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatchBrowserEvent('notify', [
                        'title' => 'Category could not be updated',
                        'body' => $validator->errors()->first(),
                        'level' => 'error',
                    ]);
                }
            });
        })->validate([
            'editValues.name' => ['required', 'max:60'],
            'editValues.parent_id' => [
                'nullable', Rule::notIn($item->id), Rule::exists('categories', 'id'),
            ],
            'editValues.groups' => ['nullable', 'array'],
            'editValues.groups.*' => [Rule::exists('groups', 'id')],
        ])['editValues'];

        $this->syncRelationship($item, $validated, 'groups');
        $item->update($validated);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;

        $this->dispatchBrowserEvent('notify', [
            'title' => 'Category was updated',
            'body' => 'The category was updated successfully.',
            'level' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.categories-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
