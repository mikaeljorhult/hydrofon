@extends('layouts.app')

@section('content')
    <main class="main-content">
        <section class="container">
            <h1>{{ $booking->start_time }} - {{ $booking->end_time }}</h1>
        </section>
    </main>
@endsection
