<?php

namespace Hydrofon\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class ResourcesTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Resource::class;
    protected $relationships = ['groups'];
    protected $editFields = ['id', 'name', 'description', 'is_facility'];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name'        => ['required', 'max:60'],
            'editValues.description' => ['nullable'],
            'editValues.is_facility' => ['nullable'],
            'editValues.groups'      => ['nullable', 'array'],
            'editValues.groups.*'    => [Rule::exists('groups', 'id')],
        ])['editValues'];

        if (isset($validatedData['groups'])) {
            $item->groups()->sync($validatedData['groups']);
            unset($validatedData['groups']);
        }

        $item->update($validatedData);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.resources-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
