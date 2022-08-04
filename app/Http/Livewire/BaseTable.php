<?php

namespace App\Http\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;
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

    protected $editFields = [
        'id' => 'ID',
        'name' => 'name',
    ];

    public $tableBaseUrl;

    public $tableDefaultSort = 'name';

    public $tableHeaders = [
        'name' => 'Name',
    ];

    protected $listeners = [
        'edit' => 'onEdit',
        'save' => 'onSave',
        'delete' => 'onDelete',
    ];

    public function __construct($id)
    {
        parent::__construct($id);

        $this->modelInstance = app($this->model);
        $this->tableBaseUrl = url()->current();
        $this->validationAttributes = Arr::prependKeysWith($this->editFields, 'editValues.');
    }

    public function mount($items, $baseUrl = null)
    {
        $this->items = $items;
        $this->selectedRows = [];
        $this->isEditing = false;

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

        $item = $query->findOrFail($id, array_keys($this->editFields));
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

        $validated = $this->withValidator(function (Validator $validator) {
            $validator->after(function (Validator $validator) {
                if ($validator->errors()->any()) {
                    $this->dispatchBrowserEvent('notify', [
                        'title' => 'Item could not be updated',
                        'body' => $validator->errors()->first(),
                        'level' => 'error',
                    ]);
                }
            });
        })->validate([
            'editValues.name' => ['required'],
        ])['editValues'];

        $item->update($validated);

        $this->refreshItems([$item->id]);
        $this->isEditing = false;

        $this->dispatchBrowserEvent('notify', [
            'title' => 'Item was updated',
            'body' => 'The item was updated successfully.',
            'level' => 'success',
        ]);
    }

    public function onDelete($id, $multiple = false)
    {
        $itemsToDelete = $multiple ? $this->selectedRows : [$id];

        $items = $this->items
            ->find($itemsToDelete)
            ->each(function ($item, $key) {
                $this->authorize('delete', $item);
            })
            ->each(function ($item, $key) {
                $item->delete();
            });

        $this->removeItems($itemsToDelete);

        if ($items->count() === 1) {
            $this->dispatchBrowserEvent('notify', [
                'title' => 'Item was deleted',
                'body' => 'The item was deleted successfully.',
                'level' => 'success',
            ]);
        } else {
            $this->dispatchBrowserEvent('notify', [
                'title' => 'Items were deleted',
                'body' => $items->count().' items were deleted successfully.',
                'level' => 'success',
            ]);
        }
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
                if (! in_array($relationship.'_id', array_keys($this->editFields)) && $item->$relationship) {
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
