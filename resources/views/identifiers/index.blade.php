@extends('layouts.app')

@section('title', 'Identifiers')

@section('content')
    <section class="container">
        <x-heading :title="'Identifiers for ' . $identifiable->name">
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route($identifiable->getTable().'.identifiers.create', [$identifiable])"
            >New identifier</x-forms.button>

            <form
                action="{{ route($identifiable->getTable().'.identifiers.store', $identifiable) }}"
                method="post"
            >
                @csrf
                <input type="hidden" name="value" value="{{ \Illuminate\Support\Str::uuid() }}" />

                <x-forms.button
                    class="rounded-full mr-2"
                    :href="route($identifiable->getTable().'.identifiers.create', [$identifiable])"
                >Generate unique ID</x-forms.button>
            </form>
        </x-heading>

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
                            <form action="{{ route($identifiable->getTable().'.identifiers.destroy', [$identifiable, $identifier]) }}" method="post">
                                @method('delete')
                                @csrf

                                <button type="submit" title="Delete">
                                    Delete
                                </button>
                            </form>
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
