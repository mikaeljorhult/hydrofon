<x-timeline.item
    :item="$item"
    :last="$loop->last"
>
    <x-slot:icon class="bg-gray-400">
        <x-heroicon-s-flag class="h-5 w-5 text-white" />
    </x-slot:icon>

    <p class="text-sm text-gray-500">
        @if($item->causer)
            <a
                href="{{ route('users.show', [$item->causer]) }}"
                class="font-medium text-gray-900"
            >{{ $item->causer->name }}</a>
        @else
            A <span class="font-medium text-gray-900">deleted user</span>
        @endif

        removed {{ $item->event }} flag
    </p>
</x-timeline.item>
