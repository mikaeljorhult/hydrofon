<?php

namespace Hydrofon\Http\Livewire;

use Livewire\Component;

class BaseTable extends Component
{
    protected $model;
    protected $modelInstance;
    protected $relationships = [];
    public $items;
    public $selectedRows;
    public $isEditing;
    public $editValues;
    protected $editFields = ['id', 'name'];

    protected $listeners = [
        'select'    => 'onSelect',
        'selectAll' => 'onSelectAll',
        'edit'      => 'onEdit',
        'save'      => 'onSave',
        'delete'    => 'onDelete',
    ];

    public function __construct($id)
    {
        parent::__construct($id);

        $this->modelInstance = app($this->model);
    }

    public function mount($items)
    {
        $this->items = $items;
        $this->selectedRows = [];
        $this->isEditing = false;
    }

    public function onSelect($id, $checked)
    {
        if ($checked) {
            array_push($this->selectedRows, $id);
            $this->selectedRows = array_values(array_unique($this->selectedRows));
        } else {
            $this->selectedRows = array_diff($this->selectedRows, [$id]);
        }
    }

    public function onSelectAll($checked)
    {
        $this->selectedRows = $checked ? $this->items->pluck('id')->toArray() : [];
    }

    public function onEdit($id)
    {
        $query = count($this->relationships) > 0
            ? $this->modelInstance->with($this->relationships)
            : $this->modelInstance;

        $item = $query->findOrFail($id, $this->editFields);
        $this->setEditValues($item);

        $this->isEditing = $id;

        // Reset validation errors when editing a new row.
        $this->validate([]);

        $this->emit('editing');
    }

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

    public function onDelete($id, $multiple)
    {
        $itemsToDelete = $multiple ? $this->selectedRows : [$id];

        $items = $this->items->find($itemsToDelete);

        $items
            ->each(function ($item, $key) {
                $this->authorize('delete', $item);
            })
            ->each(function ($item, $key) {
                $item->delete();
            });

        $this->removeItems($itemsToDelete);
    }

    protected function refreshItems($ids = [])
    {
        $this->items = empty($ids)
            ? $this->items->fresh($this->relationships)
            : $this->items->merge($this->items->find($ids)->fresh($this->relationships));
    }

    protected function removeItems($ids = [])
    {
        $this->items = empty($ids)
            ? collect()
            : $this->items->except($ids);
    }

    private function setEditValues($item): void
    {
        $this->editValues = $item->attributesToArray();

        if (count($this->relationships) > 0) {
            foreach ($this->relationships as $relationship) {
                if (!in_array($relationship.'_id', $this->editFields) && $item->$relationship) {
                    $this->editValues[$relationship] = $item->$relationship->pluck('id')->toArray();
                }
            }
        }
    }
}
