@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Bookings', 'url' => route('bookings.index')])
            <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-pill mr-2">New booking</a>

            {!! Form::open(['route' => 'bookings.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[start_time]', request('filter.start_time'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary sr-only']) !!}
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'bookings.index', 'method' => 'GET']) !!}
            <section class="lg:flex items-end py-2 px-3 bg-gray-100">
                <div class="mb-2 lg:mb-0 lg:mr-4">
                    {!! Form::label('filter[resource_id]', 'Resource', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[resource_id]', \App\Models\Resource::orderBy('name')->pluck('name', 'id'), request('filter.resource_id'), ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[user_id]', 'User', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[user_id]', \App\Models\User::orderBy('name')->pluck('name', 'id'), request('filter.user_id'), ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[start_time]', 'Start Time', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[start_time]', request('filter.start_time'), ['placeholder' => 'Start Time', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
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

        @livewire('bookings-table', ['items' => $bookings->getCollection()])

        {{ $bookings->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
