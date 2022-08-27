<div x-data="itemsTable({ selectedRows: @entangle('selectedRows').defer })">
    <table class="table">
        @include('livewire.partials.table-header')

        <tbody>
            @forelse($this->items as $item)
                <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} group hover:bg-red-50">
                    <td data-title="&nbsp;">
                        <x-forms.checkbox
                            class="text-red-500"
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

                    <td data-title="&nbsp;" class="flex justify-end">
                        @if($item->isPending)
                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-700 rounded hover:text-red-700 hover:border-red-700"
                                form="approveform-{{ $item->id }}"
                                type="submit"
                                title="Approve"
                                wire:click.prevent="$emit('approve', {{ $item->id }})"
                                wire:loading.attr="disabled"
                            ><x-heroicon-m-check class="w-4 h-4 fill-current" /></button>

                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-700 rounded hover:text-red-700 hover:border-red-700"
                                type="submit"
                                title="Reject"
                                wire:click.prevent="$emit('reject', {{ $item->id }})"
                                wire:loading.attr="disabled"
                            ><x-heroicon-m-x-mark class="w-4 h-4 fill-current" /></button>
                        @endif

                        <button
                            class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                            form="viewincalendarform-{{ $item->id }}"
                            type="submit"
                            title="View in calendar"
                        ><x-heroicon-m-calendar class="w-4 h-4 fill-current" /></button>

                        <div class="hidden">
                            <form action="{{ route('approvals.store') }}" method="post" id="approveform-{{ $item->id }}">
                                @csrf
                                <input type="hidden" name="booking_id" value="{{ $item->id }}" />
                            </form>

                            <form action="{{ route('calendar') }}" method="post" id="viewincalendarform-{{ $item->id }}">
                                @csrf
                                <input type="hidden" name="date" value="{{ $item->start_time->format('Y-m-d') }}" />
                                <input type="hidden" name="resources[]" value="{{ $item->resource->id }}" />
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
