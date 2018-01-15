@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>Identifiers for {{ $user->name }}</h1>

            <a href="{{ route('users.identifiers.create', [$user]) }}">New identifier</a>

            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Value</th>
                    <th>&nbsp;</th>
                </thead>

                <tbody>
                    @forelse($user->identifiers as $identifier)
                        <tr>
                            <td data-title="&nbsp;"></td>
                            <td data-title="Name">
                                <a href="{{ route('users.identifiers.edit', [$user, $identifier]) }}">{{ $identifier->value }}</a>
                            </td>
                            <td data-title="&nbsp;">
                                {!! Form::model($identifier, ['route' => ['users.identifiers.destroy', $user, $identifier], 'method' => 'DELETE' ]) !!}
                                    {!! Form::submit('Delete') !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">User don't have any identifiers.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>
@endsection
