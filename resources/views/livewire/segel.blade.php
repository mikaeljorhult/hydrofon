<div>
    <div class="flex justify-between items-baseline mb-4">
        <div class="calendar-header flex items-baseline">
            <h1
                class="order-2 my-0 mx-1 text-3xl"
                x-text="date"
            >{{ $this->dateString }}</h1>
            <a
                class="order-1"
                title="Previous"
                href="{{ route('calendar') }}"
                wire:click.prevent="previousTimeScope"
            >
                <x-heroicon-s-chevron-left class="w-5" />
                <span class="sr-only">Previous</span>
            </a>
            <a
                class="order-3"
                title="Next"
                href="{{ route('calendar') }}"
                wire:click.prevent="nextTimeScope"
            >
                <span class="sr-only">Next</span>
                <x-heroicon-s-chevron-right class="w-5" />
            </a>
        </div>

        <div>
            <div class="flex items-baseline">
                <x-forms.label for="type">Show:</x-forms.label>

                <x-forms.select
                    name="type"
                    id="type"
                    :options="['day' => 'Day', 'week' => 'Week', 'month' => 'Month']"
                    wire:model="type"
                    class="ml-1"
                />
            </div>
        </div>
    </div>

    <div
        id="segel"
        class="segel my-4"
        x-data="segel({
            start: {{ $this->timestamps['start'] }},
            duration: {{ $this->timestamps['duration'] }},
            steps: {{ $this->steps }}
        })"
        x-bind="base"
    >
        <div class="segel-container relative border-t-2 border-b-2 border-gray-200 min-h-[16rem]">
            <ul class="segel-grid flex absolute inset-0 top-9 pointer-events-none ml-8">
                @for($i = 0; $i < count($this->headings); $i++)
                    <li class="flex-1 bg-gray-400 {{ $i % 2 !== 0 ? 'opacity-5' : 'opacity-10' }}">&nbsp;</li>
                @endfor
            </ul>

            <aside class="segel-ruler pl-8 border-b-2 border-gray-200">
                <ul class="flex justify-between text-sm">
                    @foreach($this->headings as $heading)
                        @if($loop->odd)
                            <li class="flex-1 p-2 bg-gray-100">
                                <span>{{ $heading }}</span>
                            </li>
                        @else
                            <li class="flex-1 p-2 bg-gray-50">
                                <span class="hidden md:inline">{{ $heading }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </aside>

            <template x-if="current > start && current < start + duration">
                <div
                    class="segel-indicator w-0.5 absolute inset-y-0 z-20 pointer-events-none bg-blue-300 transition-all"
                    x-bind:style="'left: ' + (((current - start) / duration) * 100) + '%';"
                ></div>
            </template>

            <ul class="segel-resources overflow-hidden select-none">
                @forelse($items as $resource)
                    <li
                        class="segel-resource h-11 relative flex items-center ml-8 px-0 border-b border-gray-200"
                        data-id="{{ $resource->id }}"
                        x-on:createbooking="$wire.createBooking($event.detail)"
                        x-on:updatebooking="$wire.updateBooking($event.detail)"
                        x-on:deletebooking="$wire.deleteBooking($event.detail)"
                    >
                        <div class="w-8 h-full flex items-center justify-center mt-px -ml-8 border-b border-gray-200">
                            <x-forms.checkbox
                                id="selected-{{ $resource->id }}"
                                name="selected[]"
                                value="{{ $resource->id }}"
                                x-bind="selector"
                            />
                        </div>

                        <label for="selected-{{ $resource->id }}" class="pl-2">{{ $resource->name }}</label>

                        @if($resource->bookings->isNotEmpty())
                            <ul class="segel-bookings absolute inset-0 select-none">
                                @foreach($resource->bookings as $booking)
                                    <li
                                        class="segel-booking block h-full absolute inset-y-0 z-40 overflow-hidden bg-red-600 opacity-75 rounded @can('update', $booking) editable @endcan"
                                        title="{{ $booking->user->name }}"
                                        style="
                                            width: {{ $booking->duration / $this->timestamps['duration'] * 100 }}%;
                                            left: {{ ($booking->start_time->format('U') - $this->timestamps['start']) / $this->timestamps['duration'] * 100 }}%;
                                            "
                                        data-id="{{ $booking->id }}"
                                        data-user="{{ $booking->user_id }}"
                                        data-start="{{ $booking->start_time->format('U') }}"
                                        data-end="{{ $booking->end_time->format('U') }}"
                                    >
                                        <span class="segel-resize-handle segel-resize-handle__left hidden items-center w-2 h-full absolute inset-y-0 bg-red-900 opacity-50 text-white text-center">&#8942;</span>
                                        <span class="segel-resize-handle segel-resize-handle__right hidden items-center w-2 h-full absolute inset-y-0 bg-red-900 opacity-50 text-white text-center">&#8942;</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @empty
                    <li class="segel-no-resources pt-20 py-0 pb-4 text-gray-400 text-center">
                        No resources have been selected.
                    </li>
                @endforelse
            </ul>

            <div
                wire:loading
                wire:target="setResources,setTimestamps,previousTimeScope,nextTimeScope"
            >
                <div class="flex items-center justify-center absolute inset-0 bg-gray-400 bg-opacity-25">
                    <div class="flex items-center justify-center py-2 px-4 bg-red-600 text-white text-xl rounded opacity-75">
                        <x-heroicon-s-refresh class="inline-block w-5 pr-1 fill-current" />
                        LOADING...
                    </div>
                </div>
            </div>
        </div>

        <form
            x-data="multiBook({
                start_time: @js(now()->setMinutes(0)->format('Y-m-d H:i')),
                end_time: @js(now()->addHours(2)->setMinutes(0)->format('Y-m-d H:i'))
            })"
            x-bind="base"
            x-on:createbooking="$wire.createBooking($event.detail)"
        >
            <div
                class="flex items-center justify-end gap-x-4 p-4 bg-gray-50"
                x-show="selected.length > 0"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                x-cloak
            >
                <div class="flex-1">
                    <span x-text="selected.length">0</span>
                    <span x-show="selected.length === 1">resource</span>
                    <span x-show="selected.length > 1">resources</span>
                    selected
                </div>

                <div>
                    <x-forms.label for="segel-start_time" class="sr-only">Start Time</x-forms.label>
                    <x-forms.input
                        id="segel-start_time"
                        name="start_time"
                        placeholder="Start Time"
                        x-model.lazy="start_time"
                        x-ref="start_time"
                    />
                    @error('start_time')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <x-forms.label for="segel-end_time" class="sr-only">End Time</x-forms.label>
                    <x-forms.input
                        id="segel-end_time"
                        name="end_time"
                        placeholder="End Time"
                        x-model.lazy="end_time"
                        x-ref="end_time"
                    />
                    @error('end_time')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <x-forms.button>
                        Book
                    </x-forms.button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            history.pushState({
                date: null,
                timestamps: @json($this->timestamps)
            }, 'initial');
        });

        window.onpopstate = function (event) {
            window.Livewire.emitTo('segel', 'setTimestamps', event.state.timestamps);
        };

        window.livewire.on('dateChanged', (state) => {
            history.pushState({date: state.date, timestamps: state.timestamps}, state.date, state.url);
        });
    </script>
@endpush
