<aside class="sidebar flex flex-col items-center justify-between overflow-hidden bg-complementary-darkest text-complementary text-center">
    <header class="py-12 px-0 bg-brand">
        <h1 class="m-0 font-bold text-base leading-none uppercase">
            <a href="{{ url('/') }}" class="text-white">
                {{ config('app.name', 'Hydrofon') }}
            </a>
        </h1>
    </header>

    @guest
        <ul class="list-reset w-full">
            <li>
                <a href="{{ route('login') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                    Log in
                </a>
            </li>
        </ul>
    @endguest

    @auth
        <section class="user py-4 px-1 bg-brand-dark">
            <a href="{{ route('profile') }}" class="text-white">
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?d=mm"
                     class="w-8 rounded-full"
                     alt="{{ auth()->user()->name }}"/>

                <div class="pt-1 text-xs">
                    {{ auth()->user()->name }}
                </div>
            </a>
        </section>

        <nav class="main-navigation flex flex-col flex-1 items-center content-between overflow-y-scroll">
            <ul class="list-reset w-full">
                <li>
                    <a href="{{ route('home') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker hover:bg-complementary-darker">
                        @svg('calendar', 'block w-8 h-auto mt-0 mx-auto mb-1') Book
                    </a>
                </li>

                @admin
                    <li class="sidebar-link">
                        <a href="{{ route('desk') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('desktop-mac', 'block w-8 h-auto mt-0 mx-auto mb-1') Desk
                        </a>
                    </li>
                @endadmin
            </ul>

            @admin
                <ul class="list-reset w-full">
                    <li class="sidebar-link">
                        <a href="{{ route('bookings.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('calendar-multiple', 'block w-8 h-auto mt-0 mx-auto mb-1') Bookings
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('buckets.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('package', 'block w-8 h-auto mt-0 mx-auto mb-1') Buckets
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('categories.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('tag-multiple', 'block w-8 h-auto mt-0 mx-auto mb-1') Categories
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('groups.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('lock-outline', 'block w-8 h-auto mt-0 mx-auto mb-1') Groups
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('resources.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('cellphone-link', 'block w-8 h-auto mt-0 mx-auto mb-1') Resources
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('users.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('account-multiple', 'block w-8 h-auto mt-0 mx-auto mb-1') Users
                        </a>
                    </li>
                </ul>
            @endadmin

            <ul class="list-reset w-full">
                <li class="sidebar-link">
                    @if(auth()->user()->isImpersonated())
                        <a href="{{ route('impersonation') }}"
                           onclick="event.preventDefault(); document.getElementById('impersonation-form').submit();"
                           class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('account-switch', 'block w-8 h-auto mt-0 mx-auto mb-1')
                            Stop impersonation
                        </a>

                        <form id="impersonation-form" action="{{ route('impersonation') }}" method="POST" style="display: none;">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    @else
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-light hover:bg-complementary-darker">
                            @svg('power', 'block w-8 h-auto mt-0 mx-auto mb-1')
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