@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Users', 'url' => route('users.index')])
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-pill mr-2">New user</a>

            {!! Form::open(['route' => 'users.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[email]', request('filter.email'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'users.index', 'method' => 'GET']) !!}
            <section class="lg:flex py-2 px-3 bg-gray-100">
                <div class="lg:mr-4">
                    {!! Form::label('filter[email]', 'E-mail', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[email]', request('filter.name'), ['placeholder' => 'E-mail', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="lg:mr-4">
                    {!! Form::label('filter[name]', 'Name', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[name]', request('filter.name'), ['placeholder' => 'Name', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="lg:mx-4">
                    {!! Form::label('filter[groups.id]', 'Group', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[groups.id]', \Hydrofon\Group::orderBy('name')->pluck('name', 'id'), request('filter')['groups.id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="lg:mx-4 flex items-center">
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

        <table class="table" cellspacing="0">
            <thead>
                <th class="table-column-check">#</th>
                <th><a href="{{ route('users.index', ['sort' => (request('sort') === 'email' || request()->has('sort') === false ? '-' : '') . 'email'] + request()->except('page')) }}">E-mail</a></th>
                <th><a href="{{ route('users.index', ['sort' => (request('sort') === 'name' ? '-' : '') . 'name'] + request()->except('page')) }}">Name</a></th>
                <th>&nbsp;</th>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td data-title="&nbsp;"></td>
                        <td data-title="E-mail">
                            <a href="{{ route('users.edit', $user) }}">{{ $user->email }}</a>
                        </td>
                        <td data-title="Name">
                            {{ $user->name }}
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            {!! Form::open(['route' => ['users.identifiers.index', $user->id], 'method' => 'GET' ]) !!}
                            <button type="submit" title="Identifiers">
                                Identifiers
                            </button>
                            {!! Form::close() !!}

                            @can('delete', $user)
                                {!! Form::model($user, ['route' => ['users.destroy', $user->id], 'method' => 'DELETE' ]) !!}
                                <button type="submit" title="Delete">
                                    Delete
                                </button>
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No users was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $users->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
