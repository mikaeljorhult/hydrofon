<?php

namespace Hydrofon\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResourcesTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Resource::class;
    protected $editFields = ['id', 'name', 'description', 'is_facility'];

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
            'items' => $this->items,
        ]);
    }
}
