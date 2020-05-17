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
            @for($i = 0; $i < 24; $i++)
                <li>&nbsp;</li>
            @endfor
        </ul>

        <aside class="segel-ruler">
            <ul>
                @for($i = 0; $i < 24; $i++)
                    <li>
                        <span {{ now()->hour == $i ? 'class="current"' : '' }}>
                            {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </li>
                @endfor
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
                                <li class="segel-booking"
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
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            HYDROFON.Segel.component = @this;
            HYDROFON.Segel.element.querySelectorAll('.segel-resource').forEach(HYDROFON.Segel.interactions.resource);
            HYDROFON.Segel.element.querySelectorAll('.segel-booking').forEach(HYDROFON.Segel.interactions.booking)

            history.pushState({
                date: null,
                timestamps: @json($timestamps)
            }, 'initial');
        });

        document.addEventListener('livewire:load', function(event) {
            window.livewire.hook('afterDomUpdate', () => {
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
