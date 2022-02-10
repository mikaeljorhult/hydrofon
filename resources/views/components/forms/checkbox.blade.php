@props(['disabled' => false])

<input
    type="checkbox"
    {{ $attributes->merge(['class' => 'shadow-sm border-gray-300 rounded-md focus:border-gray-300 focus:ring-red-500 disabled:opacity-50']) }}
    {{ $disabled ? 'disabled="disabled"' : '' }}
/>
