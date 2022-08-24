@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
    <section class="container">
        <x-heading title="Bookings" :url="route('bookings.index')">
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('bookings.create')"
            >New booking</x-forms.button>

            <x-slot name="filters">
                <form action="{{ route('bookings.index') }}" method="get">
                    <section class="lg:flex items-end py-2 px-3 bg-gray-50">
                        <div class="items-center mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[resource_id]" class="sr-only">Resource</x-forms.label>
                            <x-forms.select name="filter[resource_id]" :options="$filterResources" :selected="request('filter')['resource_id'] ?? null" placeholder="All resources" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[user_id]" class="sr-only">User</x-forms.label>
                            <x-forms.select name="filter[user_id]" :options="$filterUsers" :selected="request('filter')['user_id'] ?? null" placeholder="All users" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[start_time]" class="sr-only">Start Time</x-forms.label>
                            <x-forms.input name="filter[start_time]" value="{{ request('filter.start_time') }}" placeholder="Start Time" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[end_time]" class="sr-only">End Time</x-forms.label>
                            <x-forms.input name="filter[end_time]" value="{{ request('filter.end_time') }}" placeholder="End Time" />
                        </div>

                        <div class="items-center mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[state]" class="sr-only">Status</x-forms.label>
                            <x-forms.select
                                name="filter[state]"
                                :options="$filterState"
                                :selected="request('filter')['state'] ?? null"
                                placeholder="All states"
                            />
                        </div>

                        <div class="flex-grow text-right">
                            @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                                <x-forms.link
                                    :href="route('bookings.index', request()->except(['filter', 'page']))"
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

        @livewire('bookings-table', ['items' => $items->getCollection()])

        {{ $items->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
