<x-booking-status
    :item="$item"
    :last="$loop->last"
>
    <x-slot:icon class="bg-gray-400">
        <x-heroicon-s-login class="h-5 w-5 text-white" />
    </x-slot:icon>

    <p class="text-sm text-gray-500">
        Checked in by <a href="#" class="font-medium text-gray-900">{{ $item->created_by->name }}</a>
    </p>
</x-booking-status>
