@props(['message'])

<div
    {{ $attributes->merge(['class' => 'p-1 text-xs text-red-600']) }}
>{{ $message }}</div>
