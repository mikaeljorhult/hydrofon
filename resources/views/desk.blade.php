@extends('layouts.app')

@section('title', 'Desk')

@section('content')
    <section class="container">
        @if($user)
            @component('components.heading', ['title' => $user->name])
            @endcomponent

            <h2>Bookings</h2>
            <table class="table">
                <thead>
                    <th><a href="{{ route('desk', ['search' => request()->route('search'), 'order' => 'resource'] + request()->except('page')) }}">Resource</a></th>
                    <th><a href="{{ route('desk', ['search' => request()->route('search'), 'order' => 'start_time'] + request()->except('page')) }}">Start</a></th>
                    <th><a href="{{ route('desk', ['search' => request()->route('search'), 'order' => 'end_time'] + request()->except('page')) }}">End</a></th>
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
                                                Check out
                                            </button>
                                        {!! Form::close() !!}
                                    @endif

                                    @unless($booking->checkin)
                                        {!! Form::open(['route' => ['checkins.store']]) !!}
                                        {!! Form::hidden('booking_id', $booking->id) !!}
                                            <button type="submit" title="Check in">
                                                Check in
                                            </button>
                                        {!! Form::close() !!}
                                    @endif

                                    {!! Form::open(['route' => 'calendar']) !!}
                                    {!! Form::hidden('date', $booking->start_time->format('Y-m-d')) !!}
                                    {!! Form::hidden('resources[]', $booking->resource->id) !!}
                                        <button type="submit" title="View in calendar">
                                            View
                                        </button>
                                    {!! Form::close() !!}

                                    {!! Form::model($booking, ['route' => ['bookings.destroy', $booking->id], 'method' => 'DELETE' ]) !!}
                                        <button type="submit" title="Delete">
                                            Delete
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
        @elseif($search)
            <p>No user was found.</p>
        @endif
    </section>
@endsection

