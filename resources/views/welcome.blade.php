@extends('layouts.guest')

@section('content')
    <section class="flex">
        <header class="flex w-1/2 h-screen flex-grow items-center justify-center">
            <div>
                <h1 class="font-bold uppercase">
                    {{ config('app.name', 'Hydrofon') }}
                </h1>

                <x-forms.button
                    type="link"
                    :href="route('login')"
                >Login</x-forms.button>
                <x-forms.button-secondary
                    type="link"
                    :href="route('register')"
                >Register</x-forms.button-secondary>
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
