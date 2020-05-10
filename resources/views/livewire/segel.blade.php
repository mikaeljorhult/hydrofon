<div
    id="segel"
    class="segel"
    x-data="{}"
    x-on:resize.window.debounce.500="HYDROFON.Segel.handleResize()"
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

        <div
            class="segel-indicator"
            x-data="{
                start: {{ $timestamps['start'] }},
                duration: {{ $timestamps['duration'] }},
                current: {{ $timestamps['current'] }},
            }"
            x-bind:style="'left: ' + (((current - start) / duration) * 100) + '%';"
            x-show="current > start && current < start + duration"
            x-cloak
        ></div>

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
        });

        document.addEventListener('livewire:load', function(event) {
            window.livewire.hook('afterDomUpdate', () => {
                HYDROFON.Segel.element.querySelectorAll('.segel-resource').forEach(HYDROFON.Segel.interactions.resource);
                HYDROFON.Segel.element.querySelectorAll('.segel-booking').forEach(HYDROFON.Segel.interactions.booking)
            });
        });
    </script>
@endpush