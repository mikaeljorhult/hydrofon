@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="container">
        <x-heading title="Categories" :url="route('categories.index')">
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('categories.create')"
            >New category</x-forms.button>

            <x-slot name="filters">
                <form action="{{ route('categories.index') }}" method="get">
                    <section class="lg:flex items-end py-2 px-3 bg-gray-50">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[name]" class="sr-only">Name</x-forms.label>
                            <x-forms.input name="filter[name]" value="{{ request('filter.name') }}" placeholder="Name" />
                        </div>

                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <x-forms.label for="filter[parent_id]" class="sr-only">Parent</x-forms.label>
                            <x-forms.select name="filter[parent_id]" :options="\App\Models\Category::orderBy('name')->pluck('name', 'id')" :selected="request('filter')['parent_id'] ?? null" placeholder="All parents" />
                        </div>

                        <div class="flex-grow text-right">
                            @if(request()->has('filter') && !empty(array_filter(request('filter'))))
                                <x-forms.link
                                    :href="route('categories.index', request()->except(['filter', 'page']))"
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

        @livewire('categories-table', ['items' => $categories->getCollection()])

        {{ $categories->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
