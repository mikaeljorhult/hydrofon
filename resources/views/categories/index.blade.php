@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Categories', 'url' => route('categories.index')])
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('categories.create')"
            >New category</x-forms.button>

            {!! Form::open(['route' => 'categories.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[categories.name]', request('filter')['categories.name'] ?? null, ['placeholder' => 'Filter', 'class' => 'field']) !!}
                <x-forms.button class="sr-only">
                    Search
                </x-forms.button>
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'categories.index', 'method' => 'GET']) !!}
            <section class="lg:flex items-end py-2 px-3 bg-gray-100">
                <div class="mb-2 lg:mb-0 lg:mr-4">
                    {!! Form::label('filter[name]', 'Name', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[name]', request('filter')['name'] ?? null, ['placeholder' => 'Name', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[parent_id]', 'Parent', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[parent_id]', \App\Models\Category::orderBy('name')->pluck('name', 'id'), request('filter')['parent_id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
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
        {!! Form::close() !!}

        @livewire('categories-table', ['items' => $categories->getCollection()])

        {{ $categories->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
