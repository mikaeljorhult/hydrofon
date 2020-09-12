<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Group::class;

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name' => ['required'],
        ])['editValues'];

        $item->update($validatedData);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.groups-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
