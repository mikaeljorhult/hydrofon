@extends('layouts.app')

@section('content')
    <main class="main-content">
        <h1>{{ $booking->start_time }} - {{ $booking->end_time }}</h1>
    </main>
@endsection
