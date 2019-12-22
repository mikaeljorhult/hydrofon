<?php

namespace Hydrofon\Http\Livewire;

use Hydrofon\Resource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\QueryBuilder\QueryBuilder;

class ResourcesTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Resource::class;
    protected $editFields = ['id', 'name', 'description', 'is_facility'];

    public function items()
    {
        $items = QueryBuilder::for($this->model)
                             ->allowedFilters(['name', 'is_facility', 'categories.id', 'groups.id'])
                             ->defaultSort('name')
                             ->allowedSorts(['name', 'description', 'is_facility'])
                             ->paginate(15);

        $this->itemIDs = $items->pluck('id')->toArray();

        return $items;
    }

    public function onSave()
    {
        $item = $this->modelInstance->findOrFail($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name'        => ['required', 'max:60'],
            'editValues.description' => ['nullable'],
            'editValues.is_facility' => ['nullable'],
        ]);

        $item->update($validatedData['editValues']);

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.resources-table', [
            'items' => $this->items(),
        ]);
    }
}
