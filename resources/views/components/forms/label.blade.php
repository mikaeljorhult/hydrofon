@props(['for'])

<label
    for="{{ $for }}"
    {{ $attributes }}
>{{ $slot }}</label>
