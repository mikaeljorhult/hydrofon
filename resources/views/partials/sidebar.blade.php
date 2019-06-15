<aside class="sidebar w-32 flex flex-col flex-shrink-0 items-center justify-between overflow-hidden bg-complementary-900 text-complementary text-center">
    <header class="w-full py-12 px-0 bg-brand">
        <h1 class="m-0 font-bold text-base leading-none uppercase">
            <a href="{{ url('/') }}" class="block text-white hover:text-white">
                {{ config('app.name', 'Hydrofon') }}
            </a>
        </h1>
    </header>

    @guest
        <ul class="list-none w-full">
            <li>
                <a href="{{ route('login') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                    @svg('stand-by', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current')
                    Log in
                </a>
            </li>
        </ul>
    @endguest

    @auth
        <section class="w-full py-4 px-1 bg-brand-600">
            <a href="{{ route('profile') }}" class="block text-white no-underline hover:text-white">
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?d=mm"
                     class="inline w-8 rounded-full"
                     alt="{{ auth()->user()->name }}"/>

                <div class="pt-1 text-xs">
                    {{ auth()->user()->name }}
                </div>
            </a>
        </section>

        <nav class="w-full flex flex-col flex-1 items-center content-between overflow-y-scroll my-6">
            <ul class="list-none w-full mb-4">
                <li>
                    <a href="{{ route('calendar') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                        @svg('calendar', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Book
                    </a>
                </li>

                @admin
                    <li>
                        <a href="{{ route('desk') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('computer-desktop', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Desk
                        </a>
                    </li>
                @endadmin
            </ul>

            @admin
                <ul class="list-none w-full mb-4">
                    <li>
                        <a href="{{ route('bookings.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('date-add', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Bookings
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('buckets.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('box', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Buckets
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('tag', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Categories
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('groups.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('lock-closed', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Groups
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('resources.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('mobile-devices', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Resources
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}" class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('user-group', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current') Users
                        </a>
                    </li>
                </ul>
            @endadmin

            <ul class="list-none w-full mb-4">
                <li>
                    @if(auth()->user()->isImpersonated())
                        <a href="{{ route('impersonation') }}"
                           onclick="event.preventDefault(); document.getElementById('impersonation-form').submit();"
                           class="block py-2 px-0 text-complementary text-xs leading-tight no-underline hover:text-complementary-400 hover:bg-complementary-800">
                            @svg('view-hide', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current')
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
                            @svg('stand-by', 'block w-6 h-auto mt-0 mx-auto mb-1 fill-current')
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