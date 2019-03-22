@extends('layouts.app')

@section('title', 'Identifiers')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Identifiers for ' . $user->name])
            <a href="{{ route('users.identifiers.create', [$user]) }}" class="btn btn-primary btn-pill mr-2">New identifier</a>
        @endcomponent

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
                        <td data-title="&nbsp;" class="table-actions">
                            {!! Form::model($identifier, ['route' => ['users.identifiers.destroy', $user, $identifier], 'method' => 'DELETE' ]) !!}
                                <button type="submit" title="Delete">
                                    Delete
                                </button>
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
@endsection
