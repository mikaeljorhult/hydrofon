@extends('layouts.app')

@section('title', 'Groups')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Groups', 'url' => route('groups.index')])
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('groups.create')"
            >New booking</x-forms.button>

            {!! Form::open(['route' => 'groups.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[name]', request('filter.name'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                <x-forms.button class="sr-only">
                    Search
                </x-forms.button>
            {!! Form::close() !!}
        @endcomponent

        @livewire('groups-table', ['items' => $groups->getCollection()])

        {{ $groups->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
