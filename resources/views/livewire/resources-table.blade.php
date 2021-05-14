<div>
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} is-editing">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Name">
                            <x-forms.input
                                name="name"
                                value="{{ $item->name }}"
                                wire:model.debounce.500ms="editValues.name"
                            />

                            @error('editValues.name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="Description">
                            <x-forms.textarea
                                name="description"
                                rows="1"
                                wire:model.debounce.500ms="editValues.description"
                            >{{ $item->description }}</x-forms.textarea>

                            @error('editValues.description')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="Facility" class="text-center">
                            <x-forms.checkbox
                                name="is_facility"
                                :checked="$item->is_facility"
                                wire:model.debounce.500ms="editValues.is_facility"
                            />

                            @error('editValues.is_facility')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="&nbsp;" class="text-right">
                            <x-forms.button
                                type="link"
                                wire:click.prevent="$emit('save')"
                                wire:loading.attr="disabled"
                            >Save</x-forms.button>

                            <x-forms.button-secondary
                                type="link"
                                wire:click.prevent="$set('isEditing', false)"
                            >Cancel</x-forms.button-secondary>
                        </td>
                    </tr>
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }}">
                        <td>&nbsp;</td>
                        <td colspan="{{ count($this->tableHeaders) + 1 }}">
                            <div class="grid gap-4 grid-cols-1 lg:grid-cols-3 pb-4">
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Groups</label>
                                    <x-forms.select
                                        name="groups[]"
                                        :options="\App\Models\Group::orderBy('name')->pluck('name', 'id')"
                                        multiple
                                        wire:model="editValues.groups"
                                    />
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Categories</label>
                                    <x-forms.select
                                        name="categories[]"
                                        :options="\App\Models\Category::orderBy('name')->pluck('name', 'id')"
                                        multiple
                                        wire:model="editValues.categories"
                                    />
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Buckets</label>
                                    <x-forms.select
                                        name="buckets[]"
                                        :options="\App\Models\Bucket::orderBy('name')->pluck('name', 'id')"
                                        multiple
                                        wire:model="editValues.buckets"
                                    />
                                </div>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} hover:bg-brand-100">
                        <td data-title="&nbsp;">
                            <x-forms.checkbox
                                value="{{ $item->id }}"
                                :checked="in_array($item->id, $this->selectedRows)"
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
                            <x-forms.checkbox
                                :checked="$item->is_facility"
                                :disabled="false"
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
                            <x-forms.button-link
                                :disabled="count($this->selectedRows) === 0"
                                wire:click.prevent="$emit('delete', false, true)"
                            >Delete</x-forms.button-link>
                        </form>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
</div>

@include('livewire.partials.table-scripts')
