@props(['disabled' => false])

@if($attributes->get('type') === 'link')
    <a
        {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-light rounded-md shadow-sm text-white no-underline bg-brand-600 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-300 disabled:opacity-50 disabled:cursor-not-allowed']) }}
        {{ $disabled ? 'disabled="disabled"' : '' }}
    >
        {{ $slot }}
    </a>
@else
    <button
        {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-light rounded-md shadow-sm text-white bg-brand-600 hover:bg-brand-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-300 disabled:opacity-50 disabled:cursor-not-allowed']) }}
        {{ $disabled ? 'disabled="disabled"' : '' }}
    >
        {{ $slot }}
    </button>
@endif
