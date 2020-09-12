<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

class UsersTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \App\Models\User::class;
    protected $relationships = ['groups'];
    protected $editFields = ['id', 'email', 'name', 'is_admin'];

    public $tableDefaultSort = 'email';
    public $tableHeaders = [
        'email'    => 'email',
        'name'     => 'Name',
        'is_admin' => 'Administrator',
    ];

    public function onSave()
    {
        $item = $this->items->find($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name'     => ['required'],
            'editValues.email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($item->id)],
            'editValues.password' => ['nullable', 'confirmed'],
            'editValues.is_admin' => ['nullable'],
            'editValues.groups'   => ['nullable', 'array'],
            'editValues.groups.*' => [Rule::exists('groups', 'id')],
        ])['editValues'];

        $this->syncRelationship($item, $validatedData, 'groups');
        $item->update($validatedData);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.users-table', [
            'items' => $this->items->loadMissing($this->relationships),
        ]);
    }
}
