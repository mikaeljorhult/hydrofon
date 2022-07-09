@props(['item', 'last' => false])

<li>
    <div class="relative pb-8">
        @unless($last)
            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
        @endif

        <div class="relative flex space-x-3">
            <div>
                <span {{ $icon->attributes->class(['h-8 w-8 flex items-center justify-center rounded-full ring-8 ring-white']) }}>
                    {{ $icon }}
                </span>
            </div>

            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                <div>{{ $slot }}</div>

                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                    <time datetime="{{ $item->created_at->toIso8601String() }}">
                        {{ $item->created_at->format('Y-m-d H:i') }}
                    </time>
                </div>
            </div>
        </div>
    </div>
</li>
