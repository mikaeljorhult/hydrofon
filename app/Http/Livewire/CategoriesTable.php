<?php

namespace Hydrofon\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class CategoriesTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Category::class;
    protected $relationships = ['parent'];
    protected $editFields = ['id', 'name', 'parent_id'];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name'      => ['required', 'max:60'],
            'editValues.parent_id' => [
                'nullable', Rule::notIn($item->id), Rule::exists('categories', 'id'),
            ],
        ]);

        $item->update($validatedData['editValues']);

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.categories-table', [
            'items' => $this->items,
        ]);
    }
}
