@extends('layouts.app')

@section('title', 'Approve Bookings')

@section('content')
    <section class="container">
        <x-heading title="Approve Bookings" :url="route('approvals.index')">
            <x-slot name="filters">
                <form action="{{ route('approvals.index') }}" method="get">
                    <section class="lg:flex items-end py-2 px-3 bg-gray-50">
                        <div class="items-center mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[resource_id]" class="sr-only">Resource</x-forms.label>
                            <x-forms.select name="filter[resource_id]" :options="\App\Models\Resource::orderBy('name')->pluck('name', 'id')" :selected="request('filter')['resource_id'] ?? null" placeholder="All resources" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[start_time]" class="sr-only">Start Time</x-forms.label>
                            <x-forms.input name="filter[start_time]" value="{{ request('filter.start_time') }}" placeholder="Start Time" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[end_time]" class="sr-only">End Time</x-forms.label>
                            <x-forms.input name="filter[end_time]" value="{{ request('filter.end_time') }}" placeholder="End Time" />
                        </div>

                        <div class="flex-grow text-right">
                            @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                                <x-forms.link
                                    :href="route('profile.bookings', request()->except(['filter', 'page']))"
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

        @livewire('approvals-table', ['items' => $bookings->getCollection()])

        {{ $bookings->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
