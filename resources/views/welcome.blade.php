@extends('layouts.guest')

@section('content')
    <section class="relative flex h-screen">
        <header class="absolute z-20 md:static flex w-full md:w-1/2 h-full md:h-screen flex-grow items-center justify-center bg-slate-100/75">
            <div class="space-y-2 md:space-y-3">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold uppercase">
                    {{ config('app.name', 'Hydrofon') }}
                </h1>

                <x-forms.button
                    type="link"
                    :href="route('login')"
                    class="w-full justify-center"
                >Login</x-forms.button>
                <x-forms.button-secondary
                    type="link"
                    :href="route('register')"
                    class="w-full justify-center"
                >Register</x-forms.button-secondary>
            </div>
        </header>

        <div
            class="absolute md:static w-full md:w-1/2 h-full md:h-screen bg-cover md:[clip-path:polygon(30%_0%,_100%_0,_100%_100%,_0%_100%)]"
            style="
                background-image: url('{{ asset('images/unsplash.jpg') }}');
                background-position: 55% center;
            "
        ></div>
    </section>
@endsection
