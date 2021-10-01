<nav class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200 md:border-none">
    <button type="button" class="px-4 border-r border-gray-200 text-gray-400 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-500 md:hidden">
        <span class="sr-only">Open sidebar</span>
        <x-heroicon-o-menu-alt-1 class="w-6 h-6" />
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
                            <x-heroicon-s-search class="w-5 h-5" />
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
            <livewire:notifications-indicator />

            <div
                class="ml-3 relative"
                x-data="{ isOpen: false }"
                x-on:click.outside="isOpen = false"
            >
                <div>
                    <button
                        type="button"
                        class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-300 lg:p-2 lg:rounded-md lg:hover:bg-gray-50"
                        id="user-menu-button"
                        aria-expanded="false"
                        aria-haspopup="true"
                        x-on:click.prevent="isOpen = !isOpen"
                    >
                        <img
                            class="h-8 w-8 rounded-full"
                            src="{{ Avatar::create(auth()->user()->name)->toBase64() }}"
                            alt="Avatar"
                        />

                        <span class="hidden ml-3 text-gray-700 text-sm font-light lg:block">
                            <span class="sr-only">Open user menu for </span>{{ auth()->user()->name }}
                        </span>

                        <x-heroicon-s-chevron-down class="hidden flex-shrink-0 ml-1 h-5 w-5 text-gray-400 lg:block" />
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
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    x-cloak
                >
                    @admin
                        <div class="px-4 py-2 text-sm text-gray-700 border-b">
                            <form action="{{ route('impersonation') }}" method="post" class="flex items-center">
                                @csrf

                                <x-forms.label for="topbar-user_id" class="sr-only">
                                    Select user to impersonate
                                </x-forms.label>

                                <x-forms.select
                                    id="topbar-user_id"
                                    name="user_id"
                                    :options="\App\Models\User::pluck('name', 'id')"
                                    :selected="session()->get('impersonate', null)"
                                    placeholder="Impersonate user..."
                                    class="!shadow-none"
                                    x-on:change="if ($el.value != '') { $el.closest('form').submit() }"
                                />

                                <x-forms.button class="sr-only">
                                    Impersonate
                                </x-forms.button>
                            </form>
                        </div>
                    @endadmin

                    <!-- Active: "bg-gray-100", Not Active: "" -->
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">Your Profile</a>

                    @if(auth()->user()->isImpersonated())
                        <a
                            class="block px-4 py-2 text-sm text-gray-700"
                            href="{{ route('impersonation') }}"
                            role="menuitem"
                            tabindex="-1"
                            id="user-menu-item-1"
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
                            id="user-menu-item-1"
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
