@extends('layouts.guest')

@section('content')
    <section class="flex">
        <header class="flex w-1/2 h-screen flex-grow items-center justify-center">
            <div>
                <h1 class="font-bold uppercase">
                    {{ config('app.name', 'Hydrofon') }}
                </h1>

                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn">Register</a>
            </div>
        </header>

        <div
            class="w-1/2 h-screen bg-cover"
            style="
                background-image: url('{{ asset('images/unsplash.jpg') }}');
                background-position: 55% center;
                -webkit-clip-path: polygon(30% 0%, 100% 0, 100% 100%, 0% 100%);
                clip-path: polygon(30% 0%, 100% 0, 100% 100%, 0% 100%);
            "
        ></div>
    </section>
@endsection