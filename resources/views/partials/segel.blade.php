<segel
        id="segel"
        class="segel"
        :resources="selectedResources"
        :bookings="bookings"
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
                    <li><span {{ now()->hour == $i ? 'class="current"' : '' }}>{{ $i }}</span></li>
                @endfor
            </ul>
        </aside>

        <ul class="segel-resources">
            @foreach($resources as $resource)
                <li class="segel-resource">
                    {{ $resource->name }}

                    @if($resource->bookings->count() > 0)
                        <ul class="segel-bookings">
                            @foreach($resource->bookings as $booking)
                                <li class="segel-booking"
                                    style="
                                            width: {{ $booking->duration / $timestamps['duration'] * 100 }}%;
                                            left: {{ ($booking->start_time->format('U') - $timestamps['start']) / $timestamps['duration'] * 100 }}%;
                                            "
                                ></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</segel>