@extends('layouts.app')

@section('content')
    <main class="main-content">
        <h1>Bookings</h1>

        <table class="table">
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
    </main>
@endsection
