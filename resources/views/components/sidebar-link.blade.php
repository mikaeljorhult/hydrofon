@props(['route', 'icon', 'text'])

<li class="w-1/2 sm:w-1/3 md:w-auto">
    <a
        href="{{ route($route) }}"
        {{ $attributes->merge(['class' => 'block py-2 px-0 text-slate-300 text-xs leading-tight no-underline hover:text-slate-100 hover:bg-slate-600']) }}
    >
        <x-dynamic-component
            :component="'heroicon-s-' . $icon"
            class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current"
        />

        {{ $text }}
    </a>
</li>
