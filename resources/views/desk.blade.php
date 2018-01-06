@extends('layouts.app')

@section('content')
    <main class="main-content">
        @include('partials/topbar')

        <section class="container">
            @if($user)
                <h1>{{ $user->name }}</h1>

                <h2>Bookings</h2>
                @if($user->bookings->count() > 0)
                    <ul>
                        @foreach($user->bookings as $booking)
                            <li>{{ $booking->object->name }}</li>
                        @endforeach
                    </ul>

                @else
                    <p>No current bookings.</p>
                @endif
            @endif
        </section>
    </main>
@endsection
