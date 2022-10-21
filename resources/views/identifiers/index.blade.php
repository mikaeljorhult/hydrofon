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
                    <tr class="{{ $loop->odd ? 'odd' : 'even bg-slate-50' }} group hover:bg-red-50">
                        <td data-title="&nbsp;"></td>
                        <td data-title="Name">
                            <a href="{{ route($identifiable->getTable().'.identifiers.edit', [$identifiable, $identifier]) }}">{{ $identifier->value }}</a>
                        </td>
                        <td data-title="&nbsp;" class="flex justify-end">
                            <a
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                href="{{ route('resources.identifiers.edit', [$identifiable, $identifier]) }}"
                                title="Edit"
                            ><x-heroicon-m-pencil class="w-4 h-4 fill-current" /></a>

                            <button
                                class="invisible group-hover:visible ml-2 p-1 border border-solid border-gray-300 text-gray-500 rounded hover:text-red-700 hover:border-red-700"
                                form="deleteform-{{ $identifier->id }}"
                                type="submit"
                                title="Delete"
                            ><x-heroicon-m-x-mark class="w-4 h-4 fill-current" /></button>

                            <div class="hidden">
                                <form action="{{ route($identifiable->getTable().'.identifiers.destroy', [$identifiable, $identifier]) }}" method="post" id="deleteform-{{ $identifier->id }}">
                                    @method('delete')
                                    @csrf
                                </form>
                            </div>
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
