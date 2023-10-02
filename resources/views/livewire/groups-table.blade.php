<div x-data="itemsTable({ selectedRows: @entangle('selectedRows') })">
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} is-editing" dusk="inline-item-{{ $item->id }}">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Name">
                            <x-forms.input
                                name="name"
                                value="{{ $item->name }}"
                                :hasErrors="$errors->has('editValues.name')"
                                wire:model.live.debounce.500ms="editValues.name"
                            />

                            @error('editValues.name')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td class="whitespace-nowrap text-right">
                            <div>
                                <x-forms.button
                                    wire:click.prevent="$dispatch('save')"
                                    wire:loading.attr="disabled"
                                    dusk="inline-save"
                                >Save</x-forms.button>

                                <x-forms.button-secondary
                                    wire:click.prevent="$set('isEditing', false)"
                                    dusk="inline-cancel"
                                >Cancel</x-forms.button-secondary>
                            </div>
                        </td>
                    </tr>

                    @if(config('hydrofon.require_approval') !== 'none')
                        <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }}">
                            <td>&nbsp;</td>
                            <td colspan="{{ count($this->headers) + 1 }}">
                                <div class="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 pb-4">
                                    <div>
                                        <label class="block mb-2 text-xs uppercase">Approvers</label>
                                        <x-forms.select
                                            name="approvers[]"
                                            :options="\App\Models\User::orderBy('name')->pluck('name', 'id')"
                                            :hasErrors="$errors->has('editValues.approvers')"
                                            multiple
                                            wire:model.live="editValues.approvers"
                                        />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                @else
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} group hover:bg-red-50" dusk="item-{{ $item->id }}">
                        <td data-title="&nbsp;">
                            <x-forms.checkbox
                                class="text-red-500"
                                name="selected[]"
                                value="{{ $item->id }}"
                                x-model="selectedRows"
                            />
                        </td>
                        <td data-title="Name">
                            <a href="{{ route('groups.edit', $item) }}">{{ $item->name }}</a>
                        </td>
                        <td data-title="&nbsp;" class="flex justify-end">
                            <a
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                href="{{ route('groups.edit', $item) }}"
                                title="Edit"
                                wire:click.prevent="$dispatch('edit', { id: {{ $item->id }} })"
                                dusk="inline-edit"
                            ><x-heroicon-m-pencil class="w-4 h-4 fill-current" /></a>

                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                form="deleteform-{{ $item->id }}"
                                type="submit"
                                title="Delete"
                                wire:click.prevent="$dispatch('delete', { id: {{ $item->id }} })"
                                wire:loading.attr="disabled"
                                dusk="delete"
                            ><x-heroicon-m-x-mark class="w-4 h-4 fill-current" /></button>

                            <div class="hidden">
                                <form action="{{ route('groups.destroy', [$item->id]) }}" method="post" id="deleteform-{{ $item->id }}">
                                    @method('delete')
                                    @csrf
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($this->headers) + 2 }}">No groups was found.</td>
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
                                wire:click.prevent="$dispatch('delete', { id: false, multiple: true })"
                            >Delete</x-forms.button-link>
                        </form>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
