@extends('layouts.app')

@section('title', 'Resources')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Resources', 'url' => route('resources.index')])
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('resources.create')"
            >New resource</x-forms.button>

            {!! Form::open(['route' => 'resources.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[name]', request('filter.name'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                <x-forms.button class="sr-only">
                    Search
                </x-forms.button>
            {!! Form::close() !!}
        @endcomponent

        {!! Form::open(['route' => 'resources.index', 'method' => 'GET']) !!}
            <section class="lg:flex items-end py-2 px-3 bg-gray-100">
                <div class="mb-2 lg:mb-0 lg:mr-4">
                    {!! Form::label('filter[name]', 'Name', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::text('filter[name]', request('filter.name'), ['placeholder' => 'Name', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[categories.id]', 'Category', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[categories.id]', \App\Models\Category::orderBy('name')->pluck('name', 'id'), request('filter')['categories.id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mb-2 lg:mb-0 lg:mx-4">
                    {!! Form::label('filter[groups.id]', 'Group', ['class' => 'lg:mr-1 text-xs uppercase']) !!}
                    {!! Form::select('filter[groups.id]', \App\Models\Group::orderBy('name')->pluck('name', 'id'), request('filter')['groups.id'] ?? null, ['placeholder' => 'All', 'class' => 'field inline-block lg:w-auto']) !!}
                </div>

                <div class="mt-4 mb-2 lg:my-0 lg:mx-4 flex items-center self-center">
                    {!! Form::checkbox('filter[is_facility]', 1, request('filter.is_facility'), ['class' => 'mr-1']) !!}
                    {!! Form::label('filter[is_facility]', 'Facility', ['class' => 'text-xs uppercase']) !!}
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
        {!! Form::close() !!}

        @livewire('resources-table', ['items' => $resources->getCollection()])

        {{ $resources->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
