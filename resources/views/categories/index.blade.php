@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Categories', 'url' => route('categories.index')])
            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-pill mr-2">New category</a>

            {!! Form::open(['route' => 'categories.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[categories.name]', request('filter')['categories.name'] ?? null, ['placeholder' => 'Filter', 'class' => 'field']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'categories.index', 'method' => 'GET']) !!}
            <section class="lg:flex items-end py-2 px-3 bg-gray-100">
                <div class="mb-2 lg:mb-0 lg:mr-4">
                    {!! Form::label('filter[categories.name]', 'Name', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[categories.name]', request('filter')['categories.name'] ?? null, ['placeholder' => 'Name', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[categories.parent_id]', 'Parent', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[categories.parent_id]', \App\Models\Category::orderBy('name')->pluck('name', 'id'), request('filter')['categories.parent_id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="flex-grow text-right">
                    @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                        <a href="{{ route('categories.index', request()->except(['filter', 'page'])) }}" class="btn btn-link">Clear</a>
                    @endif

                    {!! Form::submit('Filter', ['class' => 'btn btn-primary']) !!}
                </div>
            </section>
        {!! Form::close() !!}

        @livewire('categories-table', ['items' => $categories->getCollection()])

        {{ $categories->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
