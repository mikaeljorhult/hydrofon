<?php

namespace Hydrofon\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class UsersTable extends BaseTable
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\User::class;
    protected $editFields = ['id', 'name', 'email'];

    public function items()
    {
        $items = QueryBuilder::for($this->model)
                             ->allowedFilters(['email', 'name', 'is_admin', 'groups.id'])
                             ->defaultSort('email')
                             ->allowedSorts(['email', 'name'])
                             ->paginate(15);

        $this->itemIDs = $items->pluck('id')->toArray();

        return $items;
    }

    public function onSave()
    {
        $item = $this->modelInstance->findOrFail($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name'     => ['required'],
            'editValues.email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($item->id)],
            'editValues.password' => ['nullable', 'confirmed'],
        ]);

        $item->update($validatedData['editValues']);

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.users-table', [
            'items' => $this->items(),
        ]);
    }
}
