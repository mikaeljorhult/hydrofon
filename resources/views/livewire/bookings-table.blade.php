<div
    x-data="itemsTable({ selectedRows: @entangle('selectedRows').defer })"
    x-on:qrcoderead.window="$wire.emit('selectIdentifier', $event.detail)"
>
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                @if($this->isEditing === $item->id)
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} is-editing">
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
                        <td data-title="User">
                            <x-forms.select
                                name="user_id"
                                :options="\App\Models\User::orderBy('name')->pluck('name', 'id')"
                                wire:model="editValues.user_id"
                            />

                            @error('editValues.user_id')
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
                        @if(config('hydrofon.require_approval') !== 'none')
                            <td data-title="&nbsp;">&nbsp;</td>
                        @endif
                        <td data-title="&nbsp;" class="text-right">
                            <x-forms.button
                                type="link"
                                wire:click.prevent="$emit('save')"
                                wire:loading.attr="disabled"
                            >Save</x-forms.button>

                            <x-forms.button-secondary
                                wire:click.prevent="$set('isEditing', false)"
                            >Cancel</x-forms.button-secondary>
                        </td>
                    </tr>
                @else
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} hover:bg-red-50">
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

                            @if(count($item->resource->buckets) > 0)
                                <button
                                    wire:click.prevent="$emit('switch', {{ $item->id }})"
                                ><x-heroicon-s-refresh class="w-4 text-gray-600 fill-current" /></button>
                            @endif
                        </td>
                        <td data-title="User">
                            {{ $item->user->name }}
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

                        <td data-title="&nbsp;" class="table-actions">
                            @unless($item->resource->is_facility || $item->checkout || $item->checkin)
                                <div>
                                    <form action="{{ route('checkouts.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $item->id }}" />

                                        <button
                                            type="submit"
                                            title="Check out"
                                            wire:click.prevent="$emit('checkout', {{ $item->id }})"
                                            wire:loading.attr="disabled"
                                        >Check out</button>
                                    </form>
                                </div>
                            @endif

                            @unless($item->resource->is_facility || $item->checkin)
                                <div>
                                    <form action="{{ route('checkins.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="booking_id" value="{{ $item->id }}" />

                                        <button
                                            type="submit"
                                            title="Check in"
                                            wire:click.prevent="$emit('checkin', {{ $item->id }})"
                                            wire:loading.attr="disabled"
                                        >Check in</button>
                                    </form>
                                </div>
                            @endif

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
                                wire:click.prevent="$emit('checkout', false, true)"
                            >Check out</x-forms.button-link>
                        </form>

                        <form>
                            <x-forms.button-link
                                x-bind:disabled="selectedRows.length === 0"
                                wire:click.prevent="$emit('checkin', false, true)"
                            >Check in</x-forms.button-link>
                        </form>

                        @if(config('hydrofon.require_approval') !== 'none')
                            <form>
                                <x-forms.button-link
                                    x-bind:disabled="selectedRows.length === 0"
                                    wire:click.prevent="$emit('approve', false, true)"
                                >Approve</x-forms.button-link>
                            </form>

                            <form>
                                <x-forms.button-link
                                    x-bind:disabled="selectedRows.length === 0"
                                    wire:click.prevent="$emit('reject', false, true)"
                                >Reject</x-forms.button-link>
                            </form>
                        @endif

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
