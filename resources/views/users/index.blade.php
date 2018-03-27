@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>
                <a href="{{ route('users.index') }}">Users</a>
            </h1>

            <a href="{{ route('users.create') }}">New user</a>

            <div>
                {!! Form::open(['route' => 'users.index', 'method' => 'GET']) !!}
                    {!! Form::search('filter') !!}
                    {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
                {!! Form::close() !!}
            </div>

            <table class="table" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th><a href="{{ route('users.index', ['order' => 'email'] + request()->except('page')) }}">E-mail</a></th>
                    <th><a href="{{ route('users.index', ['order' => 'name'] + request()->except('page')) }}">Name</a></th>
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
                                @can('delete', $user)
                                    {!! Form::model($user, ['route' => ['users.destroy', $user->id], 'method' => 'DELETE' ]) !!}
                                    <button type="submit" title="Delete">
                                        @svg('delete')
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

            {{ $users->appends(['filter' => request()->get('filter')])->links() }}
        </section>
    </main>
@endsection
