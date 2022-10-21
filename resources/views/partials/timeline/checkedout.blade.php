<x-timeline.item
    :item="$item"
    :last="$loop->last"
>
    <x-slot:icon class="bg-indigo-800">
        <x-heroicon-s-arrow-right-on-rectangle class="h-5 w-5 text-indigo-100" />
    </x-slot:icon>

    <p class="text-sm text-gray-500">
        Checked out by

        @if($item->causer)
            <a
                href="{{ route('users.show', [$item->causer]) }}"
                class="font-medium text-gray-900"
            >{{ $item->causer->name }}</a>
        @else
            a <span class="font-medium text-gray-900">deleted user</span>
        @endif
    </p>
</x-timeline.item>
