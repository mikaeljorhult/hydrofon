@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => $user->name])
        @endcomponent

        <div class="md:flex">
            @if($upcoming->count() > 0)
                <div class="md:w-1/2 lg:w-1/3">
                    <h2 class="mb-2">Upcoming</h2>
                    @foreach($upcoming as $day)
                        <h3 class="mb-1 text-sm font-normal">{{ $day->first()->start_time->format('Y-m-d') }}</h3>
                        <ul class="mb-3">
                            @foreach($day as $booking)
                                <li>{{ $booking->resource->name }}</li>
                            @endforeach
                        </ul>
                    @endforeach
                </div>
            @endif

            @if($overdue->count() > 0)
                <div class="md:w-1/2 lg:w-1/3">
                    <h2 class="mb-2">Overdue</h2>
                    <ul class="mb-3">
                        @foreach($overdue as $booking)
                            <li>
                                {{ $booking->resource->name }}
                                <span class="text-sm">
                                    ({{ $booking->end_time->diffForHumans() }})
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </section>
@endsection
