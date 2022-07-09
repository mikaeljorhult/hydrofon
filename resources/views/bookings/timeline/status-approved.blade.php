@unless(config('hydrofon.require_approval') === 'none')
    <x-booking-status
        :item="$item"
        :last="$loop->last"
    >
        <x-slot:icon class="bg-green-500">
            <x-heroicon-s-check class="h-5 w-5 text-white" />
        </x-slot:icon>

        @if($item->created_by_id)
            <p class="text-sm text-gray-500">Approved by <a href="#" class="font-medium text-gray-900">{{ $item->created_by->name }}</a></p>
        @else
            <p class="text-sm text-gray-500">Approved by a deleted user</p>
        @endif
    </x-booking-status>
@endif
