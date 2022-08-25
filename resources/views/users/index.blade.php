@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <section class="container">
        <x-heading :title="'Users'" :url="route('users.index')">
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('users.create')"
            >New user</x-forms.button>

            <x-slot name="filters">
                <form action="{{ route('users.index') }}" method="get">
                    <section class="lg:flex items-end py-2 px-3 bg-gray-50">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[email]" class="sr-only">E-mail</x-forms.label>
                            <x-forms.input name="filter[email]" value="{{ request('filter.email') }}" placeholder="E-mail" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[name]" class="sr-only">Name</x-forms.label>
                            <x-forms.input name="filter[name]" value="{{ request('filter.name') }}" placeholder="Name" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[groups.id]" class="sr-only">Group</x-forms.label>
                            <x-forms.select name="filter[groups.id]" :options="$filterGroups" :selected="request('filter')['groups.id'] ?? null" placeholder="All groups" />
                        </div>

                        <div class="mt-4 mb-2 lg:my-0 lg:mr-4 flex items-center self-center">
                            <x-forms.checkbox name="filter[is_admin]" id="filter[is_admin]" :checked="request('filter.is_admin')" />
                            <x-forms.label for="filter[is_admin]" class="ml-1 text-sm">Administrator</x-forms.label>
                        </div>

                        <div class="flex-grow text-right">
                            @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                                <x-forms.link
                                    :href="route('users.index', request()->except(['filter', 'page']))"
                                >Clear</x-forms.link>
                            @endif

                            <x-forms.button>
                                Filter
                            </x-forms.button>
                        </div>
                    </section>
                </form>
            </x-slot>
        </x-heading>

        @livewire('users-table', ['items' => $items->getCollection()])

        {{ $items->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
