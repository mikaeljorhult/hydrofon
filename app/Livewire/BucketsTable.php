<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class BucketsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Bucket::class;

    protected $relationships = ['resources'];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatch('notify',
                        title: 'Bucket could not be updated',
                        body: $validator->errors()->first(),
                        level: 'error',
                    );
                }
            });
        })->validate([
            'editValues.name' => ['required'],
            'editValues.resources' => ['nullable', 'array'],
            'editValues.resources.*' => [Rule::exists('resources', 'id')],
        ])['editValues'];

        $this->syncRelationship($item, $validated, 'resources');

        $item->update($validated);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;

        $this->dispatch('notify',
            title: 'Bucket was updated',
            body: 'The bucket was updated successfully.',
            level: 'success',
        );
    }

    public function render()
    {
        return view('livewire.buckets-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
