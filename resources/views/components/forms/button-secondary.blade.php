@props(['disabled' => false])

@if($attributes->get('type') === 'link')
    <a
        {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 no-underline bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-300 disabled:opacity-50 disabled:cursor-not-allowed']) }}
        {{ $disabled ? 'disabled="disabled"' : '' }}
    >
        {{ $slot }}
    </a>
@else
    <button
        {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-300 disabled:opacity-50 disabled:cursor-not-allowed']) }}
        {{ $disabled ? 'disabled="disabled"' : '' }}
    >
        {{ $slot }}
    </button>
@endif
