<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class BucketsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Bucket::class;

    protected $relationships = ['resources'];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name' => ['required'],
            'editValues.resources' => ['nullable', 'array'],
            'editValues.resources.*' => [Rule::exists('resources', 'id')],
        ])['editValues'];

        $this->syncRelationship($item, $validatedData, 'resources');

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
