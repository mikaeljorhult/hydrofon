@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Users', 'url' => route('users.index')])
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-pill mr-2">New user</a>

            {!! Form::open(['route' => 'users.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[email]', request('filter.email'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary sr-only']) !!}
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'users.index', 'method' => 'GET']) !!}
            <section class="lg:flex items-end py-2 px-3 bg-gray-100">
                <div class="mb-2 lg:mb-0 lg:mr-4">
                    {!! Form::label('filter[email]', 'E-mail', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[email]', request('filter.email'), ['placeholder' => 'E-mail', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mr-4">
                    {!! Form::label('filter[name]', 'Name', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[name]', request('filter.name'), ['placeholder' => 'Name', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[groups.id]', 'Group', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[groups.id]', \App\Models\Group::orderBy('name')->pluck('name', 'id'), request('filter')['groups.id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mt-4 mb-2 lg:my-0 lg:mx-4 flex items-center self-center">
                    {!! Form::checkbox('filter[is_admin]', 1, request('filter.is_admin'), ['class' => 'mr-1']) !!}
                    {!! Form::label('filter[is_admin]', 'Administrator', ['class' => 'text-xs uppercase']) !!}
                </div>

                <div class="flex-grow text-right">
                    @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                        <a href="{{ route('resources.index', request()->except(['filter', 'page'])) }}" class="btn btn-link">Clear</a>
                    @endif

                    {!! Form::submit('Filter', ['class' => 'btn btn-primary']) !!}
                </div>
            </section>
        {!! Form::close() !!}

        @livewire('users-table', ['items' => $users->getCollection()])

        {{ $users->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
