<header class="heading">
    <h1>
        @isset($url)
            <a href="{{ $url }}">
        @endisset

        {{ $title }}

        @isset($url)
            </a>
        @endisset
    </h1>

    <aside>
        {{ $slot }}
    </aside>
</header>