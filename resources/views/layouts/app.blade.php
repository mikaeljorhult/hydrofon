<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#dc2626">

    <title>@yield('title', config('app.name', 'Hydrofon'))</title>

    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');

        window.HYDROFON = {
            baseURL: @json(url('/')),
            user:  @json(auth()->id()),
            isAdmin: @json(auth()->user() ? auth()->user()->isAdmin() : false)
        };
    </script>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <livewire:styles />
</head>

<body class="h-full bg-white text-gray-700 font-base font-light">
    <div id="app" class="app h-full md:flex">
        @include('partials.sidebar')
        @yield('sidebar')

        <main class="main-content h-full flex-grow overflow-scroll">
            @include('partials/topbar')
            @include('flash::message')
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('js/manifest.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <livewire:scripts />
    @stack('scripts')
</body>

</html>
