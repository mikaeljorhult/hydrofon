@props(['options' => [], 'selected' => null, 'disabled' => false])

<select
    {{ $attributes->merge(['class' => 'block w-full pl-3 pr-10 py-2 text-base font-light border-gray-300 rounded-md focus:outline-none focus:border-gray-300 focus:ring-brand-500 sm:text-sm disabled:opacity-50']) }}
    {{ $disabled ? 'disabled="disabled"' : '' }}
    x-data
    x-init="$el.classList.toggle('text-gray-500', $el.value == '')"
    x-on:change="$el.classList.toggle('text-gray-500', $el.value == '')"
>
    @if($attributes->get('placeholder'))
        <option value="">{{ $attributes->get('placeholder') }}</option>
    @endif

    @foreach($options as $key => $label)
        <option
            value="{{ $key }}"
            {{ $key == $selected ? 'selected' : '' }}
        >{{ $label }}</option>
    @endforeach
</select>
