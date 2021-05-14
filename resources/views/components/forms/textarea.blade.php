@props(['checked' => false, 'disabled' => false])

<textarea
    {{ $attributes->merge([
        'class' => 'block w-full border-gray-300 rounded-md shadow-sm font-light focus:border-gray-300 focus:ring-brand-500 sm:text-sm disabled:opacity-50',
        'row' => '3',
    ]) }}
>{{ $slot }}</textarea>
