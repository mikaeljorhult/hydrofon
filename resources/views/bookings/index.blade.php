@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>
                <a href="{{ route('bookings.index') }}">Bookings</a>
            </h1>

            <a href="{{ route('bookings.create') }}">New booking</a>

            <table class="table" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th><a href="{{ route('bookings.index', ['order' => 'resource'] + request()->except('page')) }}">Resource</a></th>
                    <th><a href="{{ route('bookings.index', ['order' => 'user'] + request()->except('page')) }}">User</a></th>
                    <th><a href="{{ route('bookings.index', ['order' => 'start_time'] + request()->except('page')) }}">Start</a></th>
                    <th><a href="{{ route('bookings.index', ['order' => 'end_time'] + request()->except('page')) }}">End</a></th>
                    <th>&nbsp;</th>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td data-title="&nbsp;"></td>
                            <td data-title="Resource">
                                <a href="{{ route('bookings.edit', $booking) }}">{{ $booking->resource->name }}</a>
                            </td>
                            <td data-title="User">
                                {{ $booking->user->name }}
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
                    @empty
                        <tr>
                            <td colspan="6">No bookings was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bookings->appends(['order' => request()->get('order')])->links() }}
        </section>
    </main>
@endsection
