<div x-data="itemsTable({ selectedRows: @entangle('selectedRows').defer })">
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} is-editing">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="E-mail">
                            <x-forms.input
                                type="email"
                                name="email"
                                value="{{ $item->email }}"
                                wire:model.debounce.500ms="editValues.email"
                            />

                            @error('editValues.email')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td data-title="Name">
                            <x-forms.input
                                name="name"
                                value="{{ $item->name }}"
                                wire:model.debounce.500ms="editValues.name"
                            />

                            @error('editValues.name')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td data-title="Administrator" class="text-center">
                            <x-forms.checkbox
                                name="is_admin"
                                :checked="$item->is_admin"
                                wire:model.debounce.500ms="editValues.is_admin"
                            />

                            @error('editValues.is_admin')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td data-title="&nbsp;" class="text-right">
                            <x-forms.button
                                wire:click.prevent="$emit('save')"
                                wire:loading.attr="disabled"
                            >Save</x-forms.button>

                            <x-forms.button-secondary
                                type="link"
                                wire:click.prevent="$set('isEditing', false)"
                            >Cancel</x-forms.button-secondary>
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
                        <td data-title="&nbsp;" class="flex justify-end">
                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                form="impersonateform-{{ $item->id }}"
                                type="submit"
                                title="Impersonate"
                            ><x-heroicon-s-eye class="w-4 h-4 fill-current" /></button>

                            <a
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                href="{{ route('users.identifiers.index', $item) }}"
                                title="Identifiers"
                            ><x-heroicon-s-tag class="w-4 h-4 fill-current" /></a>

                            <a
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                href="{{ route('users.edit', $item) }}"
                                title="Edit"
                                wire:click.prevent="$emit('edit', {{ $item->id }})"
                            ><x-heroicon-s-pencil class="w-4 h-4 fill-current" /></a>

                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                form="deleteform-{{ $item->id }}"
                                type="submit"
                                title="Delete"
                                wire:click.prevent="$emit('delete', {{ $item->id }})"
                                wire:loading.attr="disabled"
                            ><x-heroicon-s-x class="w-4 h-4 fill-current" /></button>

                            <div>
                                <form action="{{ route('impersonation') }}" method="post" id="impersonateform-{{ $item->id }}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $item->id }}" />
                                </form>
                            </div>

                            <div class="hidden">
                                <form action="{{ route('users.destroy', [$item->id]) }}" method="post" id="deleteform-{{ $item->id }}">
                                    @method('delete')
                                    @csrf
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($this->headers) + 2 }}">No users was found.</td>
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
