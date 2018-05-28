@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>{{ $booking->start_time }} - {{ $booking->end_time }}</h1>
    </section>
@endsection
