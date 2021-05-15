@props(['for'])

<label
    for="{{ $for }}"
    {{ $attributes->merge(['class' => 'text-sm']) }}
>{{ $slot }}</label>
