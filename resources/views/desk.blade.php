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
                        <th>Resource</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>&nbsp;</th>
                    </thead>

                    <tbody>
                        @if($bookings->count() > 0)
                            @foreach($bookings as $booking)
                                <tr>
                                    <td data-title="Resource">
                                        <a href="{{ route('bookings.edit', $booking) }}">{{ $booking->resource->name }}</a>
                                    </td>
                                    <td data-title="Start">
                                        {{ $booking->start_time }}
                                    </td>
                                    <td data-title="End">
                                        {{ $booking->end_time }}
                                    </td>
                                    <td data-title="&nbsp;" class="table-actions">
                                        @unless($booking->checkout)
                                            {!! Form::open(['route' => ['checkouts.store']]) !!}
                                            {!! Form::hidden('booking_id', $booking->id) !!}
                                                <button type="submit" title="Check out">
                                                    @svg('logout-variant')
                                                </button>
                                            {!! Form::close() !!}
                                        @endif

                                        @unless($booking->checkin)
                                            {!! Form::open(['route' => ['checkins.store']]) !!}
                                            {!! Form::hidden('booking_id', $booking->id) !!}
                                                <button type="submit" title="Check in">
                                                    @svg('login-variant')
                                                </button>
                                            {!! Form::close() !!}
                                        @endif

                                        {!! Form::open(['route' => 'calendar']) !!}
                                        {{ Form::hidden('date', $booking->start_time->format('Y-m-d')) }}
                                        {{ Form::hidden('resources[]', $booking->resource->id) }}
                                            <button type="submit" title="View in calendar">
                                                @svg('calendar')
                                            </button>
                                        {!! Form::close() !!}

                                        {!! Form::model($booking, ['route' => ['bookings.destroy', $booking->id], 'method' => 'DELETE' ]) !!}
                                            <button type="submit" title="Delete">
                                                @svg('delete')
                                            </button>
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

                {{ $bookings->links() }}
            @else
                <p>No user was found.</p>
            @endif
        </section>
    </main>
@endsection

