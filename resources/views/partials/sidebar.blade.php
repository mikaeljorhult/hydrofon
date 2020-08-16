<aside class="sidebar md:w-32 flex flex-col flex-shrink-0 items-center justify-between overflow-hidden bg-complementary-900 text-complementary text-center">
    <header class="w-full py-12 px-0 bg-brand">
        <h1 class="m-0 font-bold text-base leading-none uppercase">
            <a href="{{ url('/') }}" class="block text-white hover:text-white">
                {{ config('app.name', 'Hydrofon') }}
            </a>
        </h1>
    </header>

    @auth
        <section class="w-full py-4 px-1 bg-brand-600">
            <a href="{{ route('profile') }}" class="block text-white no-underline hover:text-white">
                <x-heroicon-s-user class="icon w-6 h-auto mt-0 mx-auto mb-1 opacity-75 fill-current" />

                <div class="pt-1 text-xs">
                    {{ auth()->user()->name }}
                </div>
            </a>
        </section>

        <nav class="w-full md:flex flex-col flex-1 items-center content-between overflow-y-scroll my-6">
            <ul class="list-none w-full flex flex-wrap items-center content-between px-4 md:block md:px-0 md:mb-4">
                <li class="w-1/2 sm:w-1/3 md:w-auto">
                    <a href="{{ route('calendar') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                        <x-heroicon-s-calendar class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                        Book
                    </a>
                </li>

                @admin
                    <li class="w-1/2 sm:w-1/3 md:w-auto">
                        <a href="{{ route('desk') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-desktop-computer class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Desk
                        </a>
                    </li>
                @endadmin

                @admin
                    <li class="w-1/2 sm:w-1/3 md:w-auto md:mt-3">
                        <a href="{{ route('bookings.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-calendar class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Bookings
                        </a>
                    </li>
                    <li class="w-1/2 sm:w-1/3 md:w-auto">
                        <a href="{{ route('buckets.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-archive class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Buckets
                        </a>
                    </li>
                    <li class="w-1/2 sm:w-1/3 md:w-auto">
                        <a href="{{ route('categories.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-tag class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Categories
                        </a>
                    </li>
                    <li class="w-1/2 sm:w-1/3 md:w-auto">
                        <a href="{{ route('groups.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-identification class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Groups
                        </a>
                    </li>
                    <li class="w-1/2 sm:w-1/3 md:w-auto">
                        <a href="{{ route('resources.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-device-mobile class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Resources
                        </a>
                    </li>
                    <li class="w-1/2 sm:w-1/3 md:w-auto">
                        <a href="{{ route('users.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-users class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Users
                        </a>
                    </li>
                @endadmin

                <li class="w-full mt-3 sm:mt-0 sm:w-1/3 md:w-auto md:mt-3">
                    @if(auth()->user()->isImpersonated())
                        <a href="{{ route('impersonation') }}"
                           onclick="event.preventDefault(); document.getElementById('impersonation-form').submit();"
                           class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-eye-off class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Stop impersonation
                        </a>

                        <form id="impersonation-form" action="{{ route('impersonation') }}" method="POST" style="display: none;">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    @else
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            <x-heroicon-s-logout class="block w-6 h-auto mt-0 mx-auto mb-1 fill-current" />
                            Log out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    @endif
                </li>
            </ul>
        </nav>
    @endauth
</aside>
