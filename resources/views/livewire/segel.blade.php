<div
    id="segel"
    class="segel"
    x-data="{}"
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
        ></div>

        <ul class="segel-resources">
            @forelse($resources as $resource)
                <li class="segel-resource">
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
                                ></li>
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
