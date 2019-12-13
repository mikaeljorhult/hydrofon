<?php

namespace Hydrofon\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class BucketsTable extends Component
{
    use AuthorizesRequests;

    protected $model = \Hydrofon\Bucket::class;
    protected $modelInstance;
    public $itemIDs;
    public $selectedRows;
    public $isEditing;
    public $editValues;

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
        $this->selectedRows = [];
        $this->isEditing    = false;

        $this->itemIDs = $this->itemIDs = $items->pluck('id')->toArray();
    }

    public function items()
    {
        return $this->modelInstance->find($this->itemIDs);
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
        $this->editValues = $this->modelInstance->findOrFail($id, ['id', 'name'])->toArray();

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

    public function render()
    {
        return view('livewire.buckets-table', [
            'buckets' => $this->items(),
        ]);
    }
}
