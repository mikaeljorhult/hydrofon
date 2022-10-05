@php
    $statuses = [
        'broken' => ['name' => 'Broken', 'color' => 'text-red-600'],
        'dirty' => ['name' => 'Dirty', 'color' => 'text-yellow-600'],
        'in-repair' => ['name' => 'In repair', 'color' => 'text-orange-600'],
        'missing' => ['name' => 'Missing', 'color' => 'text-gray-600'],
    ];
@endphp

<x-heroicon-s-exclamation-circle
    class="inline-block w-6 h-6 pb-1 {{ $statuses[$status]['color'] }} fill-current"
    title="{{ $statuses[$status]['name'] }}"
/>
