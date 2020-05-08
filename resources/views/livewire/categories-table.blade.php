<div>
    <table class="table">
        <thead>
            <th class="table-column-check">#</th>
            <th><a href="{{ route('categories.index', ['sort' => (request('sort') === 'categories.name' || request()->has('sort') === false ? '-' : '') . 'categories.name'] + request()->except('page')) }}">Name</a></th>
            <th><a href="{{ route('categories.index', ['sort' => (request('sort') === 'parent.name' ? '-' : '') . 'parent.name'] + request()->except('page')) }}">Parent</a></th>
            <th>&nbsp;</th>
        </thead>

        <tbody>
            @forelse($items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} is-editing">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Name">
                            <input
                                type="text"
                                name="name"
                                value="{{ $item->name }}"
                                class="field"
                                wire:model.debounce.500ms="editValues.name"
                            />

                            @error('editValues.name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="Parent">
                            <select
                                name="parent_id"
                                class="field"
                                wire:model="editValues.parent_id"
                            >
                                @foreach(\Hydrofon\Category::where('id', '!=', $item->id)->orderBy('name')->get(['id', 'name']) as $optionItem)
                                    <option value="{{ $optionItem->id }}">{{ $optionItem->name }}</option>
                                @endforeach
                            </select>

                            @error('editValues.parent_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            <a
                                class="btn btn-primary"
                                wire:click.prevent="$emit('save')"
                                wire:loading.attr="disabled"
                            >Save</a>

                            <a
                                class="btn"
                                wire:click.prevent="$set('isEditing', false)"
                            >Cancel</a>
                        </td>
                    </tr>
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }}">
                        <td>&nbsp;</td>
                        <td colspan="3">
                            <div class="flex pb-4">
                                <div class="w-full lg:w-1/2 xl:w-1/3">
                                    <label class="block mb-2 text-xs uppercase">Groups</label>
                                    <select
                                        name="groups[]"
                                        class="field"
                                        multiple
                                        wire:model="editValues.groups"
                                    >
                                        @foreach(\Hydrofon\Group::orderBy('name')->get(['id', 'name']) as $optionItem)
                                            <option value="{{ $optionItem->id }}">{{ $optionItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} hover:bg-brand-100">
                        <td data-title="&nbsp;">
                            <input
                                type="checkbox"
                                value="{{ $item->id }}"
                                {{ in_array($item->id, $this->selectedRows) ? 'checked="checked"' : '' }}
                                wire:click="$emit('select', {{ $item->id }}, $event.target.checked)"
                            />
                        </td>
                        <td data-title="Name">
                            <a href="{{ route('categories.edit', $item) }}">{{ $item->name }}</a>
                        </td>
                        <td data-title="Parent">
                            {{ optional($item->parent)->name }}
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            <div>
                                <a
                                    href="{{ route('categories.edit', $item) }}"
                                    title="Edit"
                                    wire:click.prevent="$emit('edit', {{ $item->id }})"
                                >Edit</a>
                            </div>

                            <div>
                                {!! Form::model($item, ['route' => ['categories.destroy', $item->id], 'method' => 'DELETE' ]) !!}
                                <button
                                    type="submit"
                                    title="Delete"
                                    wire:click.prevent="$emit('delete', {{ $item->id }})"
                                    wire:loading.attr="disabled"
                                >Delete</button>
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="4">No categories was found.</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th colspan="4">
                    <div class="flex justify-end">
                        <form>
                            <button
                                {{ count($this->selectedRows) === 0 ? 'disabled="disabled"' : '' }}
                                class="btn"
                                wire:click.prevent="$emit('delete', false, true)"
                            >Delete</button>
                        </form>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
</div>

@include('livewire.partials.table-scripts')
