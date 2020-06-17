@extends('layouts.app')

@section('title', 'Calendar')

@section('sidebar')
    @include('partials.resource-tree')
@endsection

@section('content')
    <section class="container">
        <div class="flex justify-between items-baseline mb-4">
            <div
                class="calendar-header flex items-baseline"
                x-data="{
                    date: '{{ $date->format('Y-m-d') }}'
                }"
                x-init="
                    window.livewire.on('dateChanged', (segelDate) => {
                        date = segelDate.date;
                    })
                "
            >
                <h1
                    class="order-2 my-0 mx-1 text-3xl"
                    x-text="date"
                >{{ $date->format('Y-m-d') }}</h1>
                <a
                    class="order-1"
                    title="Previous"
                    href="{{ route('calendar', [$date->copy()->subDay()->format('Y-m-d')]) }}"
                    x-on:click.prevent="HYDROFON.Segel.component.call('previousTimeScope');"
                >
                    @svg('cheveron-left', 'w-5')
                    <span class="screen-reader">Previous</span>
                </a>
                <a
                    class="order-3"
                    title="Next"
                    href="{{ route('calendar', [$date->copy()->addDay()->format('Y-m-d')]) }}"
                    x-on:click.prevent="HYDROFON.Segel.component.call('nextTimeScope');"
                >
                    <span class="screen-reader">Next</span>
                    @svg('cheveron-right', 'w-5')
                </a>
            </div>
        </div>

        @livewire('segel', ['resources' => $resources, 'date' => $date])
    </section>
@endsection
