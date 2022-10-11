<x-timeline.item
    :item="$item"
    :last="$loop->last"
>
    <x-slot:icon class="bg-gray-400">
        <x-heroicon-s-check class="h-5 w-5 text-white" />
    </x-slot:icon>

    <p class="text-sm text-gray-500">
        Completed
    </p>
</x-timeline.item>
