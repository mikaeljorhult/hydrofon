<div>
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even' }} is-editing">
                        <td data-title="&nbsp;">&nbsp;</td>
                        <td data-title="Resource">
                            <select
                                name="resource_id"
                                class="field"
                                wire:model="editValues.resource_id"
                            >
                                @foreach(\App\Models\Resource::orderBy('name')->get(['id', 'name']) as $optionItem)
                                    <option value="{{ $optionItem->id }}">{{ $optionItem->name }}</option>
                                @endforeach
                            </select>

                            @error('editValues.resource_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="Start">
                            <div wire:ignore>
                                <input
                                    type="text"
                                    name="start_time"
                                    value="{{ $item->start_time }}"
                                    class="field"
                                    wire:model.debounce.500ms="editValues.start_time"
                                />
                            </div>

                            @error('editValues.start_time')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="End">
                            <div wire:ignore>
                                <input
                                    type="text"
                                    name="end_time"
                                    value="{{ $item->end_time }}"
                                    class="field"
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
                            <input
                                type="checkbox"
                                value="{{ $item->id }}"
                                {{ in_array($item->id, $this->selectedRows) ? 'checked="checked"' : '' }}
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
                                {!! Form::open(['route' => 'calendar']) !!}
                                    {{ Form::hidden('date', $item->start_time->format('Y-m-d')) }}
                                    {{ Form::hidden('resources[]', $item->resource->id) }}
                                    <button type="submit" title="View in calendar">
                                        View
                                    </button>
                                {!! Form::close() !!}
                            </div>

                            <div>
                                {!! Form::model($item, ['route' => ['bookings.destroy', $item->id], 'method' => 'DELETE' ]) !!}
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
                let startTime = @this.root.el.querySelector('input[name="start_time"]');
                let endTime = @this.root.el.querySelector('input[name="end_time"]');

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
