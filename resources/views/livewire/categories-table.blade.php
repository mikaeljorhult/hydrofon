<div x-data="itemsTable({ selectedRows: @entangle('selectedRows').defer })">
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} is-editing">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Name">
                            <x-forms.input
                                name="name"
                                value="{{ $item->name }}"
                                :hasErrors="$errors->has('editValues.name')"
                                wire:model.debounce.500ms="editValues.name"
                            />

                            @error('editValues.name')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td data-title="Parent">
                            <x-forms.select
                                name="parent_id"
                                :options="\App\Models\Category::where('id', '!=', $item->id)->orderBy('name')->pluck('name', 'id')"
                                :hasErrors="$errors->has('editValues.parent_id')"
                                placeholder="-"
                                wire:model="editValues.parent_id"
                            />

                            @error('editValues.parent_id')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td class="whitespace-nowrap text-right">
                            <div>
                                <x-forms.button
                                    wire:click.prevent="$emit('save')"
                                    wire:loading.attr="disabled"
                                >Save</x-forms.button>

                                <x-forms.button-secondary
                                    wire:click.prevent="$set('isEditing', false)"
                                >Cancel</x-forms.button-secondary>
                            </div>
                        </td>
                    </tr>
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }}">
                        <td>&nbsp;</td>
                        <td colspan="{{ count($this->headers) + 1 }}">
                            <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 pb-4">
                                <div>
                                    <label class="block mb-2 text-xs uppercase">Groups</label>

                                    <x-forms.select
                                        name="groups[]"
                                        :options="\App\Models\Group::orderBy('name')->pluck('name', 'id')"
                                        :hasErrors="$errors->has('editValues.groups')"
                                        multiple
                                        wire:model="editValues.groups"
                                    />
                                </div>
                            </div>
                        </td>
                    </tr>
                @else
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} group hover:bg-red-50">
                        <td data-title="&nbsp;">
                            <x-forms.checkbox
                                class="text-red-500"
                                name="selected[]"
                                value="{{ $item->id }}"
                                x-model="selectedRows"
                            />
                        </td>
                        <td data-title="Name">
                            <a href="{{ route('categories.edit', $item) }}">{{ $item->name }}</a>
                        </td>
                        <td data-title="Parent">
                            {{ $item->parent?->name }}
                        </td>
                        <td data-title="&nbsp;" class="flex justify-end">
                            <a
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                href="{{ route('categories.edit', $item) }}"
                                title="Edit"
                                wire:click.prevent="$emit('edit', {{ $item->id }})"
                            ><x-heroicon-m-pencil class="w-4 h-4 fill-current" /></a>

                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                form="deleteform-{{ $item->id }}"
                                type="submit"
                                title="Delete"
                                wire:click.prevent="$emit('delete', {{ $item->id }})"
                                wire:loading.attr="disabled"
                            ><x-heroicon-m-x-mark class="w-4 h-4 fill-current" /></button>

                            <div class="hidden">
                                <form action="{{ route('categories.destroy', [$item->id]) }}" method="post" id="deleteform-{{ $item->id }}">
                                    @method('delete')
                                    @csrf
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($this->headers) + 2 }}">No categories was found.</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th colspan="{{ count($this->headers) + 2 }}">
                    <div class="flex justify-end">
                        <form>
                            <x-forms.button-link
                                x-bind:disabled="selectedRows.length === 0"
                                wire:click.prevent="$emit('delete', false, true)"
                            >Delete</x-forms.button-link>
                        </form>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
