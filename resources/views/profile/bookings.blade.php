@extends('layouts.app')

@section('title', 'Profile - Bookings')

@section('content')
    <section class="container">
        <x-heading :title="'Profile: Bookings'" :url="route('profile.bookings')">
            <x-slot name="filters">
                <form action="{{ route('profile.bookings') }}" method="get">
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

                        @if(config('hydrofon.require_approval') !== 'none')
                            <div class="items-center mb-2 lg:mb-0 lg:mr-4">
                                <x-forms.label for="filter[status]" class="sr-only">Status</x-forms.label>
                                <x-forms.select name="filter[status]" :options="['approved' => 'Approved', 'rejected' => 'Rejected', 'pending' => 'Pending']" :selected="request('filter')['status'] ?? null" placeholder="All statuses" />
                            </div>
                        @endif

                        <div class="mt-4 mb-2 lg:my-0 lg:mr-4 flex items-center self-center">
                            <x-forms.checkbox name="filter[overdue]" id="filter[overdue]" :checked="request('filter.overdue')" />
                            <x-forms.label for="filter[overdue]" class="ml-1 text-sm">Overdue</x-forms.label>
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

        @livewire('profile-bookings-table', ['items' => $bookings->getCollection()])

        {{ $bookings->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
