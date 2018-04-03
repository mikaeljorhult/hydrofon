@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <header class="main-header">
                <h1>Identifiers for {{ $user->name }}</h1>

                <aside>
                    <a href="{{ route('users.identifiers.create', [$user]) }}">New identifier</a>
                </aside>
            </header>

            <table class="table" cellspacing="0">
                <thead>
                    <th class="table-column-check">#</th>
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
