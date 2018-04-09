<aside class="sidebar">
    <header class="branding">
        <h1>
            <a href="{{ url('/') }}">{{ config('app.name', 'Hydrofon') }}</a>
        </h1>
    </header>

    @guest
        <section>
            <ul>
                <li>
                    <a href="{{ route('login') }}">Log in</a>
                </li>
            </ul>
        </section>
    @endguest

    @auth
        <section class="user">
            <a href="{{ route('profile') }}">
                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?d=mm"
                     class="user-avatar"
                     alt="{{ auth()->user()->name }}"/>
                <div class="user-name">{{ auth()->user()->name }}</div>
            </a>
        </section>

        <nav class="main-navigation">
            <section>
                <ul>
                    <li class="sidebar-link">
                        <a href="{{ route('home') }}">
                            @svg('calendar') Book
                        </a>
                    </li>
                    @admin
                        <li class="sidebar-link">
                            <a href="{{ route('desk') }}">
                                @svg('desktop-mac') Desk
                            </a>
                        </li>
                    @endadmin
                </ul>
            </section>

            @admin
                <section>
                    <ul>
                        <li class="sidebar-link">
                            <a href="{{ route('bookings.index') }}">
                                @svg('calendar-multiple') Bookings
                            </a>
                        </li>
                        <li class="sidebar-link">
                            <a href="{{ route('buckets.index') }}">
                                @svg('package') Buckets
                            </a>
                        </li>
                        <li class="sidebar-link">
                            <a href="{{ route('categories.index') }}">
                                @svg('tag-multiple') Categories
                            </a>
                        </li>
                        <li class="sidebar-link">
                            <a href="{{ route('groups.index') }}">
                                @svg('lock-outline') Groups
                            </a>
                        </li>
                        <li class="sidebar-link">
                            <a href="{{ route('resources.index') }}">
                                @svg('cellphone-link') Resources
                            </a>
                        </li>
                        <li class="sidebar-link">
                            <a href="{{ route('users.index') }}">
                                @svg('account-multiple') Users
                            </a>
                        </li>
                    </ul>
                </section>
            @endadmin

            <section>
                <ul>
                    <li class="sidebar-link">
                        @if(auth()->user()->isImpersonated())
                            <a href="{{ route('impersonation') }}"
                               onclick="event.preventDefault();
                            document.getElementById('impersonation-form').submit();">
                                @svg('account-switch')
                                Stop impersonation
                            </a>

                            <form id="impersonation-form" action="{{ route('impersonation') }}" method="POST" style="display: none;">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                            </form>
                        @else
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                @svg('power')
                                Log out
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        @endif
                    </li>
                </ul>
            </section>
        </nav>
    @endauth
</aside>