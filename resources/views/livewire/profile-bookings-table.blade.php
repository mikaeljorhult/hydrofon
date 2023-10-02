<div x-data="itemsTable({ selectedRows: @entangle('selectedRows') })">
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} is-editing" dusk="inline-item-{{ $item->id }}">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Resource">
                            <x-forms.select
                                name="resource_id"
                                :options="\App\Models\Resource::orderBy('name')->pluck('name', 'id')"
                                :hasErrors="$errors->has('editValues.resource_id')"
                                wire:model.live="editValues.resource_id"
                            />

                            @error('editValues.resource_id')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td data-title="Start">
                            <div wire:ignore>
                                <x-forms.input
                                    name="start_time"
                                    value="{{ $item->start_time }}"
                                    :hasErrors="$errors->has('editValues.start_time')"
                                    wire:model="editValues.start_time"
                                />
                            </div>

                            @error('editValues.start_time')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        <td data-title="End">
                            <div wire:ignore>
                                <x-forms.input
                                    name="end_time"
                                    value="{{ $item->end_time }}"
                                    :hasErrors="$errors->has('editValues.end_time')"
                                    wire:model="editValues.end_time"
                                />
                            </div>

                            @error('editValues.end_time')
                                <x-forms.error :message="$message" />
                            @enderror
                        </td>
                        @if(config('hydrofon.require_approval') !== 'none')
                            <td data-title="&nbsp;">&nbsp;</td>
                        @endif
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
                        <td data-title="Resource">
                            <a href="{{ route('bookings.edit', $item) }}">{{ $item->resource->name }}</a>
                        </td>
                        <td data-title="Start">
                            {{ $item->start_time->format('Y-m-d H:i') }}
                        </td>
                        <td data-title="End">
                            {{ $item->end_time->format('Y-m-d H:i') }}
                        </td>
                        @if(config('hydrofon.require_approval') !== 'none')
                            <td data-title="Status">
                                @include('livewire.partials.item-status', ['item' => $item])
                            </td>
                        @endif

                        <td data-title="&nbsp;" class="flex justify-end">
                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                form="viewincalendarform-{{ $item->id }}"
                                type="submit"
                                title="View in calendar"
                                dusk="viewincalendar"
                            ><x-heroicon-m-calendar class="w-4 h-4 fill-current" /></button>

                            <a
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                href="{{ route('bookings.edit', $item) }}"
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
                                <form action="{{ route('calendar') }}" method="post" id="viewincalendarform-{{ $item->id }}">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $item->start_time->format('Y-m-d') }}" />
                                    <input type="hidden" name="resources[]" value="{{ $item->resource->id }}" />
                                </form>

                                <form action="{{ route('bookings.destroy', [$item->id]) }}" method="post" id="deleteform-{{ $item->id }}">
                                    @method('delete')
                                    @csrf
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($this->headers) + 2 }}">No bookings was found.</td>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @this.on('editing', function () {
                let startTime = @this.__instance.el.querySelector('input[name="start_time"]');
                let endTime = @this.__instance.el.querySelector('input[name="end_time"]');

                flatpickr(startTime, {
                    allowInput: true,
                    enableTime: true,
                    altInput: false,
                    dateFormat: 'Y-m-d H:i',
                    time_24hr: true,
                });
                flatpickr(endTime, {
                    allowInput: true,
                    enableTime: true,
                    altInput: false,
                    dateFormat: 'Y-m-d H:i',
                    time_24hr: true,
                });
            });
        });
    </script>
@endpush
