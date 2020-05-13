@extends('layouts.app')

@section('title', 'Calendar')

@section('sidebar')
    @include('partials.resource-tree')
@endsection

@section('content')
    <section class="container">
        <div
            class="calendar-header"
            x-data="{
                date: '{{ $date->format('Y-m-d') }}'
            }"
            x-init="
                window.livewire.on('dateChanged', (segelDate) => {
                    date = segelDate.date;
                })
            "
        >
            <h1 x-text="date">{{ $date->format('Y-m-d') }}</h1>
            <a
                title="Previous"
                href="{{ route('calendar', [$date->copy()->subDay()->format('Y-m-d')]) }}"
                x-on:click.prevent="HYDROFON.Segel.component.call('previousTimeScope');"
            >
                @svg('cheveron-left', 'w-6')
                <span class="screen-reader">Previous</span>
            </a>
            <a
                title="Next"
                href="{{ route('calendar', [$date->copy()->addDay()->format('Y-m-d')]) }}"
                x-on:click.prevent="HYDROFON.Segel.component.call('nextTimeScope');"
            >
                <span class="screen-reader">Next</span>
                @svg('cheveron-right', 'w-6')
            </a>
        </div>

        @livewire('segel', ['resources' => $resources, 'timestamps' => $timestamps])
    </section>
@endsection
