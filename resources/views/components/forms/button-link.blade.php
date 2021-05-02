@props(['disabled' => false])

<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-light rounded-md text-gray-500 bg-transparent hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-300 disabled:opacity-50 disabled:cursor-not-allowed']) }}
    {{ $disabled ? 'disabled="disabled"' : '' }}
>
    {{ $slot }}
</button>
