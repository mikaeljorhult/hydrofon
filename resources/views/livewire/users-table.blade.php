<div>
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} is-editing">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="E-mail">
                            <x-forms.input
                                type="email"
                                name="email"
                                value="{{ $item->email }}"
                                wire:model.debounce.500ms="editValues.email"
                            />

                            @error('editValues.email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
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
                        <td data-title="Administrator" class="text-center">
                            <x-forms.checkbox
                                name="is_admin"
                                :checked="$item->is_admin"
                                wire:model.debounce.500ms="editValues.is_admin"
                            />

                            @error('editValues.is_admin')
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
                            <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 pb-4">
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Groups</label>
                                    <x-forms.select
                                        name="groups[]"
                                        :options="\App\Models\Group::orderBy('name')->pluck('name', 'id')"
                                        multiple
                                        wire:model="editValues.groups"
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
                        <td data-title="E-mail">
                            <a href="{{ route('users.edit', $item) }}">{{ $item->email }}</a>
                        </td>
                        <td data-title="Name">
                            <a href="{{ route('users.edit', $item) }}">{{ $item->name }}</a>
                        </td>
                        <td data-title="Administrator" class="text-center">
                            <x-forms.checkbox
                                :checked="$item->is_admin"
                                :disabled="true"
                            />
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            <div>
                                <a
                                    href="{{ route('users.edit', $item) }}"
                                    title="Edit"
                                    wire:click.prevent="$emit('edit', {{ $item->id }})"
                                >Edit</a>
                            </div>

                            <div>
                                {!! Form::open(['route' => ['users.identifiers.index', $item->id], 'method' => 'GET' ]) !!}
                                    <button type="submit" title="Identifiers">
                                        Identifiers
                                    </button>
                                {!! Form::close() !!}
                            </div>

                            <div>
                                {!! Form::model($item, ['route' => ['users.destroy', $item->id], 'method' => 'DELETE' ]) !!}
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
                    <td colspan="{{ count($this->tableHeaders) + 2 }}">No users was found.</td>
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
