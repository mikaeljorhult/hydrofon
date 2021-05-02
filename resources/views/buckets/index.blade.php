@extends('layouts.app')

@section('title', 'Buckets')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Buckets', 'url' => route('buckets.index')])
            <x-forms.button
                type="link"
                class="rounded-full mr-2"
                :href="route('buckets.create')"
            >New bucket</x-forms.button>

            {!! Form::open(['route' => 'buckets.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[name]', request('filter.name'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                <x-forms.button class="sr-only">
                    Search
                </x-forms.button>
            {!! Form::close() !!}
        @endcomponent

        @livewire('buckets-table', ['items' => $buckets->getCollection()])

        {{ $buckets->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
