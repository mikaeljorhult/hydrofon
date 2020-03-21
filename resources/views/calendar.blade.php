@extends('layouts.app')

@section('title', 'Calendar')

@section('sidebar')
    @include('partials.resource-tree')
@endsection

@section('content')
    <section class="container">
        <div class="calendar-header">
            <h1>{{ $date->format('Y-m-d') }}</h1>
            <a title="Previous">
                @svg('cheveron-left', 'w-6')
                <span class="screen-reader">Previous</span>
            </a>
            <a title="Next">
                <span class="screen-reader">Next</span>
                @svg('cheveron-right', 'w-6')
            </a>
        </div>

        @livewire('segel', ['resources' => $resources, 'timestamps' => $timestamps])
    </section>
@endsection
