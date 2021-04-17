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
                <span class="screen-reader">Previous</span>
            </a>
            <a
                class="order-3"
                title="Next"
                href="{{ route('calendar') }}"
                wire:click.prevent="nextTimeScope"
            >
                <span class="screen-reader">Next</span>
                <x-heroicon-s-chevron-right class="w-5" />
            </a>
        </div>

        <div>
            <div class="flex items-baseline">
                <label class="inline-block mr-1">
                    Show:
                </label>

                <select
                    class="field"
                    wire:model="type"
                >
                    <option value="day">Day</option>
                    <option value="week">Week</option>
                    <option value="month">Month</option>
                </select>
            </div>
        </div>
    </div>

    <div
        id="segel"
        class="segel"
        x-data="{
            start: {{ $timestamps['start'] }},
            duration: {{ $timestamps['duration'] }},
            current: 0,
        }"
        x-on:resize.window.debounce.500="HYDROFON.Segel.handleResize()"
        x-init="
            setInterval(() => {
                current = Math.round(
                    (new Date().getTime() - (new Date().getTimezoneOffset() * 60 * 1000))
                    / 1000
                );
            }, 1000);
        "
    >
        <div class="segel-container">
            <ul class="segel-grid">
                @for($i = 0; $i < count($this->headings); $i++)
                    <li>&nbsp;</li>
                @endfor
            </ul>

            <aside class="segel-ruler">
                <ul>
                    @foreach($this->headings as $heading)
                        <li>
                            <span>{{ $heading }}</span>
                        </li>
                    @endforeach
                </ul>
            </aside>

            <template x-if="current > start && current < start + duration">
                <div
                    class="segel-indicator"
                    x-bind:style="'left: ' + (((current - start) / duration) * 100) + '%';"
                ></div>
            </template>

            <ul class="segel-resources">
                @forelse($items as $resource)
                    <li
                        class="segel-resource"
                        data-id="{{ $resource->id }}"
                    >
                        {{ $resource->name }}

                        @if($resource->bookings->count() > 0)
                            <ul class="segel-bookings">
                                @foreach($resource->bookings as $booking)
                                    <li
                                        class="segel-booking @can('update', $booking) editable @endcan"
                                        title="{{ $booking->user->name }}"
                                        style="
                                            width: {{ $booking->duration / $timestamps['duration'] * 100 }}%;
                                            left: {{ ($booking->start_time->format('U') - $timestamps['start']) / $timestamps['duration'] * 100 }}%;
                                            "
                                        data-id="{{ $booking->id }}"
                                        data-user="{{ $booking->user_id }}"
                                        data-start="{{ $booking->start_time->format('U') }}"
                                        data-end="{{ $booking->end_time->format('U') }}"
                                    >
                                        <span class="segel-resize-handle segel-resize-handle__left">&#8942;</span>
                                        <span class="segel-resize-handle segel-resize-handle__right">&#8942;</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @empty
                    <li class="segel-no-resources">
                        No resources have been selected.
                    </li>
                @endforelse
            </ul>

            <div
                wire:loading
                wire:target="setResources,setTimestamps,previousTimeScope,nextTimeScope"
            >
                <div class="flex items-center justify-center absolute inset-0 bg-gray-400 bg-opacity-25">
                    <div class="flex items-center justify-center py-2 px-4 bg-brand text-white text-xl rounded opacity-75">
                        <x-heroicon-s-refresh class="inline-block w-5 pr-1 fill-current" />
                        LOADING...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            HYDROFON.Segel.component = @this.__instance;
            HYDROFON.Segel.element.querySelectorAll('.segel-resource').forEach(HYDROFON.Segel.interactions.resource);
            HYDROFON.Segel.element.querySelectorAll('.segel-booking').forEach(HYDROFON.Segel.interactions.booking)

            history.pushState({
                date: null,
                timestamps: @json($timestamps)
            }, 'initial');
        });

        document.addEventListener('livewire:load', function(event) {
            window.livewire.hook('message.processed', () => {
                HYDROFON.Segel.handleResize();
                HYDROFON.Segel.element.querySelectorAll('.segel-resource').forEach(HYDROFON.Segel.interactions.resource);
                HYDROFON.Segel.element.querySelectorAll('.segel-booking').forEach(HYDROFON.Segel.interactions.booking)
            });
        });

        window.onpopstate = function (event) {
            HYDROFON.Segel.component.call('setTimestamps', event.state.timestamps);
        };

        window.livewire.on('dateChanged', (state) => {
            history.pushState({date: state.date, timestamps: state.timestamps}, state.date, state.url);
        })
    </script>
@endpush
