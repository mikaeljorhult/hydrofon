@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        <header class="calendar-header">
            <h1>{{ $date->format('Y-m-d') }}</h1>
            <a href="{{ route('calendar', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}">Previous</a>
            <a href="{{ route('calendar', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}">Next</a>
        </header>

        @include('partials.segel')
    </main>
@endsection
