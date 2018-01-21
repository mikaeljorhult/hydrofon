@extends('layouts.app')

@section('content')
    <main class="main-content">
        @include('partials/topbar')

        <section class="container">
            @if($user)
                <h1>{{ $user->name }}</h1>

                <h2>Bookings</h2>
                <table class="table" cellspacing="0">
                    <thead>
                        <th>Object</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>&nbsp;</th>
                    </thead>

                    <tbody>
                        @if($user->bookings->count() > 0)
                            @foreach($user->bookings as $booking)
                                <tr>
                                    <td data-title="Object">
                                        <a href="{{ route('bookings.edit', $booking) }}">{{ $booking->object->name }}</a>
                                    </td>
                                    <td data-title="Start">
                                        {{ $booking->start_time }}
                                    </td>
                                    <td data-title="End">
                                        {{ $booking->end_time }}
                                    </td>
                                    <td data-title="&nbsp;" class="table-actions">
                                        @unless($booking->checkin)
                                            {!! Form::open(['route' => ['checkins.store']]) !!}
                                                {!! Form::hidden('booking_id', $booking->id) !!}
                                                {!! Form::submit('Check in') !!}
                                            {!! Form::close() !!}
                                        @endunless

                                        @unless($booking->checkout)
                                            {!! Form::open(['route' => ['checkouts.store']]) !!}
                                                {!! Form::hidden('booking_id', $booking->id) !!}
                                                {!! Form::submit('Check out') !!}
                                            {!! Form::close() !!}
                                        @endunless

                                        {!! Form::open(['route' => 'calendar']) !!}
                                            {{ Form::hidden('date', $booking->start_time->format('Y-m-d')) }}
                                            {{ Form::hidden('objects[]', $booking->object->id) }}
                                            {!! Form::submit('View') !!}
                                        {!! Form::close() !!}

                                        {!! Form::model($booking, ['route' => ['bookings.destroy', $booking->id], 'method' => 'DELETE' ]) !!}
                                            {!! Form::submit('Delete') !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No bookings was found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @else
                <p>No user was found.</p>
            @endif
        </section>
    </main>
@endsection

