<button
    type="button"
    class="bg-white p-1 rounded-full {{ $hasUnreadNotifications ? 'text-red-600 hover:text-red-700' : 'text-gray-400 hover:text-gray-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-300"
    wire:poll.keep-alive.30s
>
    <span class="sr-only">View notifications</span>
    <x-heroicon-o-bell class="w-6 h-6" />
</button>
