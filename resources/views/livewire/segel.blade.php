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
        ></div>

        <ul class="segel-resources">
            @forelse($items as $resource)
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

            let bookings = HYDROFON.Segel.element.querySelectorAll('.segel-booking');

            for (const booking of bookings) {
                const position = {x: 0, y: 0};

                interact(booking)
                    .draggable({
                        listeners: {
                            start: function(event) {
                                event.target.classList.add('is-dragging');
                            },
                            move: function (event) {
                                position.x += event.dx;
                                position.y += event.dy;

                                event.target.style.transform = `translate(${position.x}px, ${position.y}px)`;
                            },
                            end: function (event) {
                                event.target.classList.remove('is-dragging');
                            }
                        },
                        modifiers: [
                            interact.modifiers.restrict({
                                restriction: '.segel-resources'
                            }),
                            interact.modifiers.snap({
                                targets: HYDROFON.Segel.grid,
                                offset: 'startCoords'
                            })
                        ],
                    });

                interact(booking)
                    .resizable({
                        edges: {
                            top: false,
                            bottom: false,
                            left: ".segel-resize-handle__left",
                            right: ".segel-resize-handle__right"
                        },
                        listeners: {
                            start: function(event) {
                                event.target.classList.add('is-resizing');
                            },
                            move: function (event) {
                                let {x, y} = event.target.dataset;

                                x = (parseFloat(x) || 0) + event.deltaRect.left;
                                y = (parseFloat(y) || 0) + event.deltaRect.top;

                                Object.assign(event.target.style, {
                                    width: `${event.rect.width}px`,
                                    height: `${event.rect.height}px`,
                                    transform: `translate(${x}px, ${y}px)`
                                });

                                Object.assign(event.target.dataset, {x, y});
                            },
                            end: function (event) {
                                event.target.classList.remove('is-resizing');
                            }
                        },
                        modifiers: [
                            interact.modifiers.restrict({
                                restriction: '.segel-resources'
                            }),
                            interact.modifiers.restrictSize(
                                HYDROFON.Segel.size
                            ),
                            interact.modifiers.snap({
                                targets: HYDROFON.Segel.grid,
                                offset: 'startCoords'
                            }),
                        ],
                    })
            }
        });
    </script>
@endpush
