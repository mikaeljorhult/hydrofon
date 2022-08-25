@extends('layouts.app')

@section('title', 'Resources')

@section('content')
    <section class="container">
        <x-heading title="Resources" :url="route('resources.index')">
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('resources.create')"
            >New resource</x-forms.button>

            <x-slot name="filters">
                <form action="{{ route('resources.index') }}" method="get">
                    <section class="lg:flex items-end py-2 px-3 bg-gray-50">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[name]" class="sr-only">Name</x-forms.label>
                            <x-forms.input name="filter[name]" value="{{ request('filter.name') }}" placeholder="Name" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[categories.id]" class="sr-only">Category</x-forms.label>
                            <x-forms.select name="filter[categories.id]" :options="$filterCategories" :selected="request('filter')['categories.id'] ?? null" placeholder="All categories" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[groups.id]" class="sr-only">Group</x-forms.label>
                            <x-forms.select name="filter[groups.id]" :options="$filterGroups" :selected="request('filter')['groups.id'] ?? null" placeholder="All groups" />
                        </div>

                        <div class="mt-4 mb-2 lg:my-0 lg:mr-4 flex items-center self-center">
                            <x-forms.checkbox name="filter[is_facility]" id="filter[is_facility]" :checked="request('filter.is_facility')" />
                            <x-forms.label for="filter[is_facility]" class="ml-1 text-sm">Facility</x-forms.label>
                        </div>

                        <div class="flex-grow text-right">
                            @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                                <x-forms.link
                                    :href="route('resources.index', request()->except(['filter', 'page']))"
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

        @livewire('resources-table', ['items' => $items->getCollection()])

        {{ $items->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
