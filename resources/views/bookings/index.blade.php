@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Bookings', 'url' => route('bookings.index')])
            <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-pill mr-2">New booking</a>

            {!! Form::open(['route' => 'bookings.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[start_time]', request('filter.start_time'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'bookings.index', 'method' => 'GET']) !!}
            <section class="lg:flex py-2 px-3 bg-gray-100">
                <div class="lg:mr-4">
                    {!! Form::label('filter[resource_id]', 'Resource', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[resource_id]', \Hydrofon\Resource::orderBy('name')->pluck('name', 'id'), request('filter.resource_id'), ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="lg:mx-4">
                    {!! Form::label('filter[user_id]', 'User', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[user_id]', \Hydrofon\User::orderBy('name')->pluck('name', 'id'), request('filter.user_id'), ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="lg:mx-4">
                    {!! Form::label('filter[start_time]', 'Start Time', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[start_time]', request('filter.start_time'), ['placeholder' => 'Start Time', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="lg:mx-4">
                    {!! Form::label('filter[end_time]', 'End Time', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[end_time]', request('filter.end_time'), ['placeholder' => 'End Time', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="flex-grow text-right">
                    @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                        <a href="{{ route('bookings.index', request()->except(['filter', 'page'])) }}" class="btn btn-link">Clear</a>
                    @endif

                    {!! Form::submit('Filter', ['class' => 'btn btn-primary']) !!}
                </div>
            </section>
        {!! Form::close() !!}

        <table class="table" cellspacing="0">
            <thead>
                <th class="table-column-check">#</th>
                <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'resources.name' ? '-' : '') . 'resources.name'] + request()->except('page')) }}">Resource</a></th>
                <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'users.name' ? '-' : '') . 'users.name'] + request()->except('page')) }}">User</a></th>
                <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'start_time' || request()->has('sort') === false ? '-' : '') . 'start_time'] + request()->except('page')) }}">Start</a></th>
                <th><a href="{{ route('bookings.index', ['sort' => (request('sort') === 'end_time' ? '-' : '') . 'end_time'] + request()->except('page')) }}">End</a></th>
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
                                {{ Form::hidden('date', $booking->start_time->format('Y-m-d')) }}
                                {{ Form::hidden('resources[]', $booking->resource->id) }}
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
                @empty
                    <tr>
                        <td colspan="6">No bookings was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $bookings->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
