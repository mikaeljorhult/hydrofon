@props(['checked' => false, 'disabled' => false, 'hasErrors' => false])

<textarea
    {{ $attributes->merge([
        'class' => 'block w-full border-gray-300 rounded-md shadow-sm font-light focus:border-gray-300 focus:ring-slate-400 sm:text-sm disabled:opacity-50'.($hasErrors ? ' ring-2 ring-red-400' : ''),
        'row' => '3',
    ]) }}
>{{ $slot }}</textarea>
