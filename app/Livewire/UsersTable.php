<?php

namespace App\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UsersTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\User::class;

    protected $relationships = ['groups'];

    protected $editFields = [
        'id' => 'ID',
        'email' => 'e-mail',
        'name' => 'name',
        'is_admin' => 'administrator',
    ];

    public $tableDefaultSort = 'email';

    public $tableHeaders = [
        'email' => 'email',
        'name' => 'Name',
        'is_admin' => 'Administrator',
    ];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatch('notify',
                        title: 'User could not be updated',
                        body: $validator->errors()->first(),
                        level: 'error',
                    );
                }
            });
        })->validate([
            'editValues.name' => ['required'],
            'editValues.email' => ['required', 'email', Rule::unique('users', 'email')->ignore($item->id)],
            'editValues.is_admin' => ['nullable'],
            'editValues.groups' => ['nullable', 'array'],
            'editValues.groups.*' => [Rule::exists('groups', 'id')],
        ])['editValues'];

        $this->syncRelationship($item, $validated, 'groups');
        $item->update($validated);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;

        $this->dispatch('notify',
            title: 'User was updated',
            body: 'The user was updated successfully.',
            level: 'success',
        );
    }

    public function render()
    {
        return view('livewire.users-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
