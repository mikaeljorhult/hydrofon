<?php

namespace App\Http\Livewire;

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

    public $tableBaseUrl;

    public $tableDefaultSort = 'name';

    public $tableHeaders = [
        'name' => 'Name',
    ];

    protected $listeners = [
        'edit'   => 'onEdit',
        'save'   => 'onSave',
        'delete' => 'onDelete',
    ];

    public function __construct($id)
    {
        parent::__construct($id);

        $this->modelInstance = app($this->model);
        $this->tableBaseUrl  = url()->current();
    }

    public function mount($items, $baseUrl = null)
    {
        $this->items        = $items;
        $this->selectedRows = [];
        $this->isEditing    = false;

        if ($baseUrl) {
            $this->tableBaseUrl = $baseUrl;
        }
    }

    public function getHeadersProperty()
    {
        return $this->tableHeaders;
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
        $this->resetValidation();

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

    public function onDelete($id, $multiple = false)
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

        $this->selectedRows = array_diff($this->selectedRows, $ids);
    }

    private function setEditValues($item)
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

    protected function syncRelationship($item, &$data, $relationship)
    {
        if (isset($data[$relationship])) {
            $item->$relationship()->sync($data[$relationship]);
            unset($data[$relationship]);
        }
    }
}
