@props(['options' => [], 'selected' => null, 'disabled' => false, 'hasErrors' => false, 'search'])

@php
    $selected = \Illuminate\Support\Arr::wrap($selected);
@endphp

<div
    class="relative mt-1"
    x-data="{
        isOpen: false,
    }"
>
    <x-forms.input
        role="combobox"
        aria-controls="options"
        aria-expanded="false"
        autocomplete="off"
        :attributes="$search->attributes"
        x-on:focus="if ($root.querySelector('[type=radio]') !== null) { isOpen = true }"
    />

    <button
        type="button"
        class="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none"
        x-on:click="isOpen = !isOpen"
    >
        <x-heroicon-m-chevron-up-down class="h-5 w-5 text-gray-400" />
    </button>

    @if($options)
        <ul
            class="absolute z-10 mt-1 max-h-40 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
            id="{{ $attributes->get('name') }}-options"
            role="listbox"
            x-show="isOpen"
        >
            @foreach($options as $key => $label)
                <li class="relative cursor-default select-none py-2 pl-3 pr-9 text-gray-900" id="{{ $attributes->get('name') }}-option-{{ $key }}" role="option" tabindex="-1">
                    <div class="flex items-baseline">
                        <label class="flex-grow cursor-pointer truncate {{ in_array($key, $selected) ? 'font-semibold' : '' }}">
                            <input
                                type="radio"
                                value="{{ $key }}"
                                {{ $attributes->class('sr-only') }}
                                x-on:click="isOpen = !isOpen"
                            />
                            {{ $label }}
                        </label>
                    </div>

                    @if(in_array($key, $selected))
                        <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600">
                            <x-heroicon-m-check class="h-5 w-5 text-gray-400" />
                        </span>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <span
            class="absolute z-10 mt-1 max-h-20 w-full overflow-auto rounded-md bg-white py-2 pl-3 pr-9 text-base shadow-lg sm:text-sm text-gray-600"
            x-show="isOpen"
            x-on:click.outside="isOpen = false"
        >No available resources.</span>
    @endif
</div>
