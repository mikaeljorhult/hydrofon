<nav class="relative z-50 shrink-0 flex h-16 bg-white border-b border-gray-200 md:border-none">
    <button
        type="button"
        class="px-4 border-r border-gray-200 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500 md:hidden"
        x-data="{ resourceListPresent: !!document.getElementById('resourcelist') }"
        x-on:click.prevent="$dispatch('show-resourcelist')"
        x-show="resourceListPresent"
        x-cloak
    >
        <span class="sr-only">Open sidebar</span>
        <x-heroicon-o-bars-3 class="w-6 h-6" />
    </button>

    <div class="flex items-center px-2 bg-red-600 text-white font-bold text-sm leading-none uppercase md:hidden">
        <a href="{{ route('home') }}">Hydrofon</a>
    </div>

    <button
        type="button"
        class="absolute top-full right-6 mt-px p-2 bg-white md:hidden"
        x-data="{ visible: false }"
        x-on:click.prevent="visible = !visible; $dispatch('toggle-sidebar', visible)"
    >
        <x-heroicon-s-chevron-double-up x-bind:class="{ 'w-4 h-4': true, 'hidden': !visible }" />
        <x-heroicon-s-chevron-double-down x-bind:class="{ 'w-4 h-4': true, 'hidden': visible }" />
    </button>

    <div class="flex-1 px-4 flex justify-between sm:px-6">
        <div class="flex-1 flex">
            @admin
                <form action="{{ route('desk') }}" method="post" class="w-full flex md:ml-0">
                    @csrf

                    <x-forms.label for="search" class="sr-only">
                        Search for resource or user
                    </x-forms.label>

                    <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                        <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none" aria-hidden="true">
                            <x-heroicon-s-magnifying-glass class="w-5 h-5" />
                        </div>

                        <input
                            type="search"
                            id="search"
                            name="search"
                            class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 font-light placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-transparent sm:text-sm"
                            placeholder="Search for resource or user"
                        />
                    </div>
                </form>
            @endadmin
        </div>

        <div class="ml-4 flex items-center md:ml-6">
            @if(request()->routeIs('desk'))
                <x-qr-scanner />
            @endif

            <livewire:quick-book />
            <livewire:notifications-indicator />

            <div
                class="ml-3 relative"
                x-data="{ isOpen: false }"
                x-on:click.outside="isOpen = false"
            >
                <div>
                    <button
                        type="button"
                        class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-300 lg:p-2 lg:rounded-md lg:hover:bg-gray-50"
                        id="user-menu-button"
                        aria-expanded="false"
                        aria-haspopup="true"
                        x-on:click.prevent="isOpen = !isOpen"
                    >
                        <x-heroicon-o-user-circle class="h-7 w-7 rounded-full text-gray-400" />

                        <span class="hidden ml-3 text-gray-700 text-sm font-light lg:block">
                            <span class="sr-only">Open user menu for </span>{{ auth()->user()->name }}
                        </span>

                        <x-heroicon-s-chevron-down class="hidden shrink-0 ml-1 h-5 w-5 text-gray-400 lg:block" />
                    </button>
                </div>

                <div
                    class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="user-menu-button"
                    tabindex="-1"

                    x-show="isOpen"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    x-cloak
                >
                    <!-- Active: "bg-gray-100", Not Active: "" -->
                    <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</a>
                    @endif

                    <hr />

                    @if(auth()->user()->isImpersonated())
                        <a
                            class="block px-4 py-2 text-sm text-gray-700"
                            href="{{ route('impersonation') }}"
                            role="menuitem"
                            tabindex="-1"
                            id="user-menu-item-2"
                            onclick="event.preventDefault(); document.getElementById('impersonation-form').submit();"
                        >Stop impersonation</a>

                        <form id="impersonation-form" action="{{ route('impersonation') }}" method="POST" style="display: none;">
                            @method('delete')
                            @csrf
                        </form>
                    @else
                        <a
                            class="block px-4 py-2 text-sm text-gray-700"
                            href="{{ route('logout') }}"
                            role="menuitem"
                            tabindex="-1"
                            id="user-menu-item-2"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        >Logout</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</nav>
