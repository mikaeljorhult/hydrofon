<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class GroupsTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\Group::class;

    protected $relationships = ['approvers'];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatchBrowserEvent('notify', [
                        'title' => 'Group could not be updated',
                        'body' => $validator->errors()->first(),
                        'level' => 'error',
                    ]);
                }
            });
        })->validate([
            'editValues.name' => ['required'],
            'editValues.approvers' => ['nullable', 'array'],
            'editValues.approvers.*' => [Rule::exists('users', 'id')],
        ])['editValues'];

        $this->syncRelationship($item, $validated, 'approvers');
        $item->update($validated);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;

        $this->dispatchBrowserEvent('notify', [
            'title' => 'Group was updated',
            'body' => 'The group was updated successfully.',
            'level' => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.groups-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
