<?php

namespace Hydrofon\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class CategoriesTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Category::class;
    protected $relationships = ['parent'];
    protected $editFields = ['id', 'name', 'parent_id'];

    public function items()
    {
        $items = QueryBuilder::for($this->model)
                             ->with($this->relationships)
                             ->leftJoin('categories as parent', 'parent.id', '=', 'categories.parent_id')
                             ->allowedFilters('categories.name', 'categories.parent_id')
                             ->allowedSorts(['categories.name', 'parent.name'])
                             ->defaultSort('categories.name')
                             ->select('categories.*')
                             ->paginate(15);

        $this->itemIDs = $items->pluck('id')->toArray();

        return $items;
    }

    public function onSave()
    {
        $item = $this->modelInstance->findOrFail($this->editValues['id']);

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
            'items' => $this->items(),
        ]);
    }
}
