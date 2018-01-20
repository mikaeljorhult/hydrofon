@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>
                <a href="{{ route('users.index') }}">Users</a>
            </h1>

            <a href="{{ route('users.create') }}">New user</a>

            <table class="table" cellspacing="0">
                <thead>
                    <th>#</th>
                    <th>E-mail</th>
                    <th>Name</th>
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
                            <td data-title="&nbsp;">
                                @can('delete', $user)
                                    {!! Form::model($user, ['route' => ['users.destroy', $user->id], 'method' => 'DELETE' ]) !!}
                                        {!! Form::submit('Delete') !!}
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

            {{ $users->links() }}
        </section>
    </main>
@endsection
