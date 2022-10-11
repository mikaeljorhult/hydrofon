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

            <x-timeline :activities="$activities" />
        </div>
    </section>
@endsection
