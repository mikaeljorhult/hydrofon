@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading title="Booking details" />

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <dl>
                <div>
                    <dt>Time</dt>
                    <dd>{{ $booking->start_time }} - {{ $booking->end_time }}</dd>
                </div>

                <div>
                    <dt>Resource</dt>
                    <dd>{{ $booking->resource->name }}</dd>
                </div>

                <div>
                    <dt>User</dt>
                    <dd>{{ $booking->user->name }}</dd>
                </div>
            </dl>

            <div>
                <h2 class="mb-4 text-base">Timeline</h2>

                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        @foreach($events as $event)
                            @includeFirst(['bookings.timeline.'.$event->type.'-'.$event->name, 'bookings.timeline.'.$event->name, 'bookings.timeline.status'], ['item' => $event, 'last' => $loop->last])
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection
