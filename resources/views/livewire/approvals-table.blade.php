<div>
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                <tr class="{{ $loop->odd ? 'odd' : 'even' }} hover:bg-brand-100">
                    <td data-title="&nbsp;">
                        <x-forms.checkbox
                            value="{{ $item->id }}"
                            :checked="in_array($item->id, $this->selectedRows)"
                            wire:click="$emit('select', {{ $item->id }}, $event.target.checked)"
                        />
                    </td>
                    <td data-title="Resource">
                        {{ $item->resource->name }}
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

                    <td data-title="&nbsp;" class="table-actions">
                        @unless($item->approval)
                            <div>
                                <form action="{{ route('approvals.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="booking_id" value="{{ $item->id }}" />

                                    <button
                                        type="submit"
                                        title="Approve"
                                        wire:click.prevent="$emit('approve', {{ $item->id }})"
                                        wire:loading.attr="disabled"
                                    >Approve</button>
                                </form>
                            </div>
                        @endif

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
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($this->tableHeaders) + 2 }}">No bookings are awaiting your approval.</td>
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
                                wire:click.prevent="$emit('approve', false, true)"
                            >Approve</x-forms.button-link>
                        </form>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
</div>

@include('livewire.partials.table-scripts')
