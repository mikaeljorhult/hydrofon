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
