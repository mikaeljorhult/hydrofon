@extends('layouts.app')

@section('title', 'Resources')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Resources', 'url' => route('resources.index')])
            <a href="{{ route('resources.create') }}" class="btn btn-primary btn-pill mr-2">New resource</a>

            {!! Form::open(['route' => 'resources.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[name]', request('filter.name'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'resources.index', 'method' => 'GET']) !!}
            <section class="lg:flex items-end py-2 px-3 bg-gray-100">
                <div class="mb-2 lg:mb-0 lg:mr-4">
                    {!! Form::label('filter[name]', 'Name', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[name]', request('filter.name'), ['placeholder' => 'Name', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[categories.id]', 'Category', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[categories.id]', \Hydrofon\Category::orderBy('name')->pluck('name', 'id'), request('filter')['categories.id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[groups.id]', 'Group', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[groups.id]', \Hydrofon\Group::orderBy('name')->pluck('name', 'id'), request('filter')['groups.id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mt-4 mb-2 lg:my-0 lg:mx-4 flex items-center self-center">
                    {!! Form::checkbox('filter[is_facility]', 1, request('filter.is_facility'), ['class' => 'mr-1']) !!}
                    {!! Form::label('filter[is_facility]', 'Facility', ['class' => 'text-xs uppercase']) !!}
                </div>

                <div class="flex-grow text-right">
                    @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                        <a href="{{ route('resources.index', request()->except(['filter', 'page'])) }}" class="btn btn-link">Clear</a>
                    @endif

                    {!! Form::submit('Filter', ['class' => 'btn btn-primary']) !!}
                </div>
            </section>
        {!! Form::close() !!}

        @livewire('resources-table')
    </section>
@endsection
