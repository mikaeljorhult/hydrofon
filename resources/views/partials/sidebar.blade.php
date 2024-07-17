<aside
    class="sidebar md:w-32 md:!flex flex-col shrink-0 items-center justify-between overflow-hidden bg-slate-800 text-white text-center"

    x-data="{ visible: false }"
    x-on:toggle-sidebar.window="visible = $event.detail;"
    x-show="visible"
    x-cloak
>
    <header class="w-full py-12 px-0 bg-red-600">
        <h1 class="m-0 font-bold text-base leading-none uppercase">
            <a href="{{ url('/') }}" class="block text-white hover:text-white">
                {{ config('app.name', 'Hydrofon') }}
            </a>
        </h1>
    </header>

    @auth
        <section class="w-full px-1 bg-red-700">
            <a
                href="{{ route('profile') }}"
                class="block py-8 text-white no-underline hover:text-white"
                title="{{ auth()->user()->name }}"
            >
                <x-heroicon-s-user class="icon w-6 h-6 mt-0 mx-auto opacity-75 fill-current" />
            </a>
        </section>

        <nav class="w-full md:flex flex-col flex-1 items-center content-between overflow-y-auto my-6">
            <ul class="list-none w-full flex flex-wrap items-center content-between px-4 md:block md:px-0 md:mb-4">
                <x-sidebar-link route="calendar" icon="calendar" text="Book" />

                @admin
                    <x-sidebar-link route="desk" icon="computer-desktop" text="Desk" />

                    <x-sidebar-link route="bookings.index" icon="calendar" text="Bookings" class="md:mt-3" />
                    <x-sidebar-link route="buckets.index" icon="archive-box" text="Buckets" />
                    <x-sidebar-link route="categories.index" icon="tag" text="Categories" />
                    <x-sidebar-link route="groups.index" icon="identification" text="Groups" />
                    <x-sidebar-link route="resources.index" icon="device-phone-mobile" text="Resources" />
                    <x-sidebar-link route="users.index" icon="users" text="Users" />
                @endadmin
            </ul>
        </nav>
    @endauth
</aside>
