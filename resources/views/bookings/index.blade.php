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
                    <th>Object</th>
                    <th>User</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>&nbsp;</th>
                </thead>

                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td data-title="&nbsp;"></td>
                            <td data-title="Object">
                                <a href="{{ route('bookings.edit', $booking) }}">{{ $booking->object->name }}</a>
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
                            <td data-title="&nbsp;">
                                @unless($booking->checkin)
                                    {!! Form::open(['route' => ['checkins.store']]) !!}
                                        {!! Form::hidden('booking_id', $booking->id) !!}
                                        {!! Form::submit('Check in') !!}
                                    {!! Form::close() !!}
                                @endif

                                @unless($booking->checkout)
                                    {!! Form::open(['route' => ['checkouts.store']]) !!}
                                        {!! Form::hidden('booking_id', $booking->id) !!}
                                        {!! Form::submit('Check out') !!}
                                    {!! Form::close() !!}
                                @endif

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
                    @empty
                        <tr>
                            <td colspan="6">No bookings was found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $bookings->links() }}
        </section>
    </main>
@endsection
