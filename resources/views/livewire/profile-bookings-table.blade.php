<div>
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} is-editing">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Resource">
                            <x-forms.select
                                name="resource_id"
                                :options="\App\Models\Resource::orderBy('name')->pluck('name', 'id')"
                                wire:model="editValues.resource_id"
                            />

                            @error('editValues.resource_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="Start">
                            <div wire:ignore>
                                <x-forms.input
                                    name="start_time"
                                    value="{{ $item->start_time }}"
                                    wire:model.debounce.500ms="editValues.start_time"
                                />
                            </div>

                            @error('editValues.start_time')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="End">
                            <div wire:ignore>
                                <x-forms.input
                                    name="end_time"
                                    value="{{ $item->end_time }}"
                                    wire:model.debounce.500ms="editValues.end_time"
                                />
                            </div>

                            @error('editValues.end_time')
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
                @else
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} hover:bg-brand-100">
                        <td data-title="&nbsp;">
                            <x-forms.checkbox
                                value="{{ $item->id }}"
                                :checked="in_array($item->id, $this->selectedRows)"
                                wire:click="$emit('select', {{ $item->id }}, $event.target.checked)"
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

                        <td data-title="&nbsp;" class="table-actions">
                            <div>
                                <a
                                    href="{{ route('bookings.edit', $item) }}"
                                    title="Edit"
                                    wire:click.prevent="$emit('edit', {{ $item->id }})"
                                >Edit</a>
                            </div>

                            <div>
                                <form action="{{ route('calendar') }}" method="post">
                                    @csrf

                                    <input type="hidden" name="date" value="{{ $item->start_time->format('Y-m-d') }}" />
                                    <input type="hidden" name="resources[]" value="{{ $item->resource->id }}" />

                                    <button type="submit" title="View in calendar">
                                        View
                                    </button>
                                </form>
                            </div>

                            <div>
                                <form action="{{ route('bookings.destroy', [$item->id]) }}" method="post">
                                    @method('delete')
                                    @csrf

                                    <button
                                        type="submit"
                                        title="Delete"
                                        wire:click.prevent="$emit('delete', {{ $item->id }})"
                                        wire:loading.attr="disabled"
                                    >Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="{{ count($this->tableHeaders) + 2 }}">No bookings was found.</td>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @this.on('editing', function () {
                let startTime = @this.__instance.el.querySelector('input[name="start_time"]');
                let endTime = @this.__instance.el.querySelector('input[name="end_time"]');

                flatpickr(startTime, {
                    allowInput: true,
                    enableTime: true,
                    altInput: true,
                    altFormat: "Y-m-d H:i",
                    dateFormat: 'Y-m-d H:i:S',
                    time_24hr: true,
                });
                flatpickr(endTime, {
                    allowInput: true,
                    enableTime: true,
                    altInput: true,
                    altFormat: "Y-m-d H:i",
                    dateFormat: 'Y-m-d H:i:S',
                    time_24hr: true,
                });
            });
        });
    </script>
@endpush
