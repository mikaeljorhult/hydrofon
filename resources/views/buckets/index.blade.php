@extends('layouts.app')

@section('title', 'Buckets')

@section('content')
    <section class="container">
        <x-heading title="Buckets" :url="route('buckets.index')">
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('buckets.create')"
            >New bucket</x-forms.button>

            <x-slot name="filters">
                <form action="{{ route('buckets.index') }}" method="get">
                    <section class="lg:flex items-end py-2 px-3 bg-gray-50">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[name]" class="sr-only">Name</x-forms.label>
                            <x-forms.input name="filter[name]" value="{{ request('filter.name') }}" placeholder="Name" />
                        </div>

                        <div class="flex-grow text-right">
                            @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                                <x-forms.link
                                    :href="route('buckets.index', request()->except(['filter', 'page']))"
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

        @livewire('buckets-table', ['items' => $buckets->getCollection()])

        {{ $buckets->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
