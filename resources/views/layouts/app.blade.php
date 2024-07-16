<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full no-js">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#b91c1c">

    <title>@yield('title', config('app.name', 'Hydrofon'))</title>

    <script>
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
    </script>

    @vite('resources/css/app.css')
    <livewire:styles />
</head>

<body class="h-full bg-white text-gray-700 font-base font-light">
    <div id="app" class="app h-full md:flex">
        @include('partials.sidebar')
        @yield('sidebar')

        <main class="h-full flex-grow overflow-y-scroll overflow-x-hidden bg-slate-100">
            @include('partials/topbar')
            @yield('content')
        </main>

        <x-flaggspel />
    </div>

    @vite('resources/js/app.js')
    @livewireScriptConfig
    @stack('scripts')
</body>

</html>
