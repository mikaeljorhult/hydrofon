@extends('layouts.app')

@section('content')
    <main class="main-content">
        <table class="table">
            <thead>
                <th>#</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>&nbsp;</th>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td data-title="&nbsp;"></td>
                        <td data-title="Name">
                            <a href="{{ route('users.edit', $user) }}">{{ $user->name }}</a>
                        </td>
                        <td data-title="E-mail">
                            {{ $user->email }}
                        </td>
                        <td data-title="&nbsp;">
                            {!! Form::model($user, ['route' => ['users.destroy', $user->id], 'method' => 'DELETE' ]) !!}
                                {!! Form::submit('Delete') !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No users was found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
@endsection
