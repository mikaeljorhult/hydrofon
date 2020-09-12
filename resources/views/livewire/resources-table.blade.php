<div>
    <table class="table">
        @include('livewire.partials.table-header')

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
                        <td data-title="Description">
                            <textarea
                                name="description"
                                cols="20"
                                rows="1"
                                class="field"
                                wire:model.debounce.500ms="editValues.description"
                            >{{ $item->description }}</textarea>

                            @error('editValues.description')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="Facility" class="text-center">
                            <input
                                type="checkbox"
                                name="is_facility"
                                {{ $item->is_facility ? 'checked="checked"' : '' }}
                                wire:model.debounce.500ms="editValues.is_facility"
                            />

                            @error('editValues.is_facility')
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
                        <td colspan="{{ count($this->tableHeaders) + 1 }}">
                            <div class="grid gap-4 grid-cols-1 lg:grid-cols-3 pb-4">
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Groups</label>
                                    <select
                                        name="groups[]"
                                        class="field"
                                        multiple
                                        wire:model="editValues.groups"
                                    >
                                        @foreach(\App\Models\Group::orderBy('name')->get(['id', 'name']) as $optionItem)
                                            <option value="{{ $optionItem->id }}">{{ $optionItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Categories</label>
                                    <select
                                        name="categories[]"
                                        class="field"
                                        multiple
                                        wire:model="editValues.categories"
                                    >
                                        @foreach(\App\Models\Category::orderBy('name')->get(['id', 'name']) as $optionItem)
                                            <option value="{{ $optionItem->id }}">{{ $optionItem->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Buckets</label>
                                    <select
                                        name="buckets[]"
                                        class="field"
                                        multiple
                                        wire:model="editValues.buckets"
                                    >
                                        @foreach(\App\Models\Bucket::orderBy('name')->get(['id', 'name']) as $optionItem)
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
                            <a href="{{ route('resources.edit', $item) }}">{{ $item->name }}</a>
                        </td>
                        <td data-title="Description">
                            {{ $item->description }}
                        </td>
                        <td data-title="Facility" class="text-center">
                            <input
                                {{ $item->is_facility ? 'checked="checked"' : '' }}
                                disabled="disabled"
                                type="checkbox"
                            />
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            <div>
                                <a
                                    href="{{ route('resources.edit', $item) }}"
                                    title="Edit"
                                    wire:click.prevent="$emit('edit', {{ $item->id }})"
                                >Edit</a>
                            </div>

                            <div>
                                {!! Form::open(['route' => ['resources.identifiers.index', $item->id], 'method' => 'GET' ]) !!}
                                    <button type="submit" title="Identifiers">
                                        Identifiers
                                    </button>
                                {!! Form::close() !!}
                            </div>

                            <div>
                                {!! Form::model($item, ['route' => ['resources.destroy', $item->id], 'method' => 'DELETE' ]) !!}
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
                    <td colspan="{{ count($this->tableHeaders) + 2 }}">No resources was found.</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th colspan="{{ count($this->tableHeaders) + 2 }}">
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
