@extends('layouts.app')

@section('title', 'Buckets')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Buckets', 'url' => route('buckets.index')])
            <a href="{{ route('buckets.create') }}" class="btn btn-primary btn-pill mr-2">New bucket</a>

            {!! Form::open(['route' => 'buckets.index', 'method' => 'GET']) !!}
                {!! Form::search('filter[name]', request('filter.name'), ['placeholder' => 'Filter', 'class' => 'field']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        @endcomponent

        @livewire('buckets-table', ['items' => $buckets->getCollection()])

        {{ $buckets->appends(['filter' => request()->get('filter'), 'sort' => request()->get('sort')])->links() }}
    </section>
@endsection
