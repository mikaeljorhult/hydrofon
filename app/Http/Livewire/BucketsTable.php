<?php

namespace Hydrofon\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BucketsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Bucket::class;

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
        return view('livewire.buckets-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
