<x-timeline.item
    :item="$item"
    :last="$loop->last"
>
    <x-slot:icon class="bg-gray-400">
        <x-heroicon-s-pencil class="h-5 w-5 text-white" />
    </x-slot:icon>

    <p class="text-sm text-gray-500">
        Updated by

        @if($item->causer)
            <a
                href="{{ route('users.show', [$item->causer]) }}"
                class="font-medium text-gray-900"
            >{{ $item->causer->name }}</a>
        @else
            a <span class="font-medium text-gray-900">deleted user</span>
        @endif

        <br />

        @php
            $editedAttributes = collect($activity->changes()['old'])->keys()->map(function ($attribute) {
                return match ($attribute) {
                    'user_id' => 'user',
                    'resource_id' => 'resource',
                    'start_time' => 'start time',
                    'end_time' => 'end time',
                };
            });
        @endphp
        <span class="text-xs">Attributes changed: {{ $editedAttributes->join(', ') }}</span>
    </p>
</x-timeline.item>
