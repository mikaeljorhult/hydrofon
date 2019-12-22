<?php

namespace Hydrofon\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\QueryBuilder\QueryBuilder;

class BaseTable extends Component
{
    use WithPagination;

    protected $model;
    protected $modelInstance;
    protected $relationships = [];
    public $itemIDs;
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

    public function mount()
    {
        $this->selectedRows = [];
        $this->isEditing    = false;
    }

    public function items()
    {
        $items = QueryBuilder::for($this->model)
                             ->allowedFilters($this->editFields)
                             ->defaultSort($this->editFields[1])
                             ->allowedSorts($this->editFields)
                             ->paginate(15);

        $this->itemIDs = $items->pluck('id')->toArray();

        if (count($this->relationships) > 0) {
            $items->load($this->relationships);
        }

        return $items;
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
        $this->selectedRows = $checked ? $this->itemIDs : [];
    }

    public function onEdit($id)
    {
        $query = count($this->relationships) > 0
            ? $this->modelInstance->with($this->relationships)
            : $this->modelInstance;

        $this->editValues = $query->findOrFail($id, $this->editFields)->toArray();

        $this->isEditing = $id;
    }

    public function onSave()
    {
        $item = $this->modelInstance->findOrFail($this->editValues['id']);

        $this->authorize('update', $item);

        $validatedData = $this->validate([
            'editValues.name' => ['required'],
        ]);

        $item->update($validatedData['editValues']);

        $this->isEditing = false;
    }

    public function onDelete($id, $multiple)
    {
        $itemsToDelete = $multiple ? $this->selectedRows : [$id];

        $items = $this->modelInstance->findOrFail($itemsToDelete);

        $items
            ->each(function ($item, $key) {
                $this->authorize('delete', $item);
            })
            ->each(function ($item, $key) {
                $item->delete();
            });
    }
}
