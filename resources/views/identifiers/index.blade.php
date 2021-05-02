@extends('layouts.app')

@section('title', 'Identifiers')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Identifiers for ' . $identifiable->name])
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route($identifiable->getTable().'.identifiers.create', [$identifiable]"
            >New identifier</x-forms.button>
        @endcomponent

        <table class="table">
            <thead>
                <th class="table-column-check">#</th>
                <th>Value</th>
                <th>&nbsp;</th>
            </thead>

            <tbody>
                @forelse($identifiable->identifiers as $identifier)
                    <tr>
                        <td data-title="&nbsp;"></td>
                        <td data-title="Name">
                            <a href="{{ route($identifiable->getTable().'.identifiers.edit', [$identifiable, $identifier]) }}">{{ $identifier->value }}</a>
                        </td>
                        <td data-title="&nbsp;" class="table-actions">
                            {!! Form::model($identifier, ['route' => [$identifiable->getTable().'.identifiers.destroy', $identifiable, $identifier], 'method' => 'DELETE' ]) !!}
                                <button type="submit" title="Delete">
                                    Delete
                                </button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">{{ class_basename($identifiable) }} don't have any identifiers.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection
