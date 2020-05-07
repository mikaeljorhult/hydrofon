<div>
    <table class="table">
        <thead>
            <th class="table-column-check">
                <input
                    type="checkbox"
                    {{ count($this->selectedRows) === count($items) ? 'checked="checked"' : '' }}
                    wire:click="$emit('selectAll', $event.target.checked)"
                />
            </th>
            <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'resources.name' ? '-' : '') . 'resources.name'] + request()->except('page')) }}">Resource</a></th>
            <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'users.name' ? '-' : '') . 'users.name'] + request()->except('page')) }}">User</a></th>
            <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'start_time' || request()->has('sort') === false ? '-' : '') . 'start_time'] + request()->except('page')) }}">Start</a></th>
            <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'end_time' ? '-' : '') . 'end_time'] + request()->except('page')) }}">End</a></th>
            <th>&nbsp;</th>
        </thead>

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
                                @foreach(\Hydrofon\Resource::orderBy('name')->get(['id', 'name']) as $optionItem)
                                    <option value="{{ $optionItem->id }}">{{ $optionItem->name }}</option>
                                @endforeach
                            </select>

                            @error('editValues.resource_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </td>
                        <td data-title="User">
                            <select
                                name="user_id"
                                class="field"
                                wire:model="editValues.user_id"
                            >
                                @foreach(\Hydrofon\User::orderBy('name')->get(['id', 'name']) as $optionItem)
                                    <option value="{{ $optionItem->id }}">{{ $optionItem->name }}</option>
                                @endforeach
                            </select>

                            @error('editValues.user_id')
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

                            @if(count($item->resource->buckets) > 0)
                                <button
                                    wire:click.prevent="$emit('switch', {{ $item->id }})"
                                >@svg('refresh', 'w-4')</button>
                            @endif
                        </td>
                        <td data-title="User">
                            {{ $item->user->name }}
                        </td>
                        <td data-title="Start">
                            {{ $item->start_time }}
                        </td>
                        <td data-title="End">
                            {{ $item->end_time }}
                        </td>

                        <td data-title="&nbsp;" class="table-actions">
                            @unless($item->resource->is_facility || $item->checkout || $item->checkin)
                                <div>
                                    {!! Form::open(['route' => ['checkouts.store']]) !!}
                                        {!! Form::hidden('booking_id', $item->id) !!}
                                        <button
                                            type="submit"
                                            title="Check out"
                                            wire:click.prevent="$emit('checkout', {{ $item->id }})"
                                            wire:loading.attr="disabled"
                                        >Check out</button>
                                    {!! Form::close() !!}
                                </div>
                            @endif

                            @unless($item->resource->is_facility || $item->checkin)
                                <div>
                                    {!! Form::open(['route' => ['checkins.store']]) !!}
                                        {!! Form::hidden('booking_id', $item->id) !!}
                                        <button
                                            type="submit"
                                            title="Check in"
                                            wire:click.prevent="$emit('checkin', {{ $item->id }})"
                                            wire:loading.attr="disabled"
                                        >Check in</button>
                                    {!! Form::close() !!}
                                </div>
                            @endif

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
                                <a
                                    href="{{ route('bookings.edit', $item) }}"
                                    title="Edit"
                                    wire:click.prevent="$emit('edit', {{ $item->id }})"
                                >Edit</a>
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
                    <td colspan="6">No bookings was found.</td>
                </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr>
                <th colspan="6">
                    <div class="flex justify-end">
                        <form>
                            <button
                                {{ count($this->selectedRows) === 0 ? 'disabled="disabled"' : '' }}
                                class="btn"
                                wire:click.prevent="$emit('checkout', false, true)"
                            >Check out</button>
                        </form>

                        <form>
                            <button
                                {{ count($this->selectedRows) === 0 ? 'disabled="disabled"' : '' }}
                                class="btn"
                                wire:click.prevent="$emit('checkin', false, true)"
                            >Check in</button>
                        </form>

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
