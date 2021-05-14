@props(['checked' => false, 'disabled' => false])

<input
    {{ $attributes->merge([
        'class' => 'block w-full shadow-sm border-gray-300 rounded-md font-light focus:border-gray-300 focus:ring-brand-500 sm:text-sm disabled:opacity-50',
        'type' => 'text',
    ]) }}
    {{ $disabled ? 'disabled="disabled"' : '' }}
/>
