@props(['href'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 no-underline bg-transparent hover:text-red-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300']) }}>
    {{ $slot }}
</a>
