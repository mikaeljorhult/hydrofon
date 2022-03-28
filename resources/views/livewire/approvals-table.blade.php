<div x-data="itemsTable({ selectedRows: @entangle('selectedRows').defer })">
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} hover:bg-red-50">
                    <td data-title="&nbsp;">
                        <x-forms.checkbox
                            name="selected[]"
                            value="{{ $item->id }}"
                            x-model="selectedRows"
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
                    <td data-title="Status">
                        @include('livewire.partials.item-status', ['item' => $item])
                    </td>

                    <td data-title="&nbsp;" class="table-actions">
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

                        <div>
                            <button
                                type="submit"
                                title="Reject"
                                wire:click.prevent="$emit('reject', {{ $item->id }})"
                                wire:loading.attr="disabled"
                            >Reject</button>
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
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($this->headers) + 2 }}">No bookings are awaiting your approval.</td>
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
                                wire:click.prevent="$emit('approve', false, true)"
                            >Approve</x-forms.button-link>
                        </form>

                        <form>
                            <x-forms.button-link
                                x-bind:disabled="selectedRows.length === 0"
                                wire:click.prevent="$emit('reject', false, true)"
                            >Reject</x-forms.button-link>
                        </form>
                    </div>
                </th>
            </tr>
        </tfoot>
    </table>
</div>
