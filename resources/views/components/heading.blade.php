<header
    {{ $attributes->merge(['class' => 'mb-4']) }}
    x-data="{ filters: {{ request()->has('filter') && !empty(array_filter(request('filter'))) ? 'true' : 'false' }} }"
>
    <div class="flex justify-between items-baseline">
        <h1>
            @isset($url)
                <a href="{{ $url }}">{{ $title }}</a>
            @else
                {{ $title }}
            @endisset
        </h1>

        <aside class="flex items-center">
            {{ $slot }}

            @isset($filters)
                <x-forms.button-secondary
                    type="button"
                    x-on:click.prevent="filters = !filters"
                >
                    <x-heroicon-o-funnel class="block w-5 h-auto mx-auto text-gray-500" />
                    <span class="sr-only">Filter</span>
                </x-forms.button-secondary>
            @endisset
        </aside>
    </div>

    @isset($filters)
        <div
            x-show="filters"
            x-cloak
        >{{ $filters }}</div>
    @endif
</header>
