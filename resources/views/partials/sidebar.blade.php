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
                            <svg viewBox="0 0 24 24">
                                <path fill="#000000" d="M19,19H5V8H19M16,1V3H8V1H6V3H5C3.89,3 3,3.89 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5C21,3.89 20.1,3 19,3H18V1M17,12H12V17H17V12Z" />
                            </svg>
                            Book
                        </a>
                    </li>
                    @admin
                        <li class="sidebar-link">
                            <a href="{{ route('desk') }}">
                                <svg viewBox="0 0 24 24">
                                    <path fill="#000000" d="M21,14H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10L8,21V22H16V21L14,18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z" />
                                </svg>
                                Desk
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
                            <svg viewBox="0 0 24 24">
                                <path fill="#000000" d="M21,17V8H7V17H21M21,3A2,2 0 0,1 23,5V17A2,2 0 0,1 21,19H7C5.89,19 5,18.1 5,17V5A2,2 0 0,1 7,3H8V1H10V3H18V1H20V3H21M3,21H17V23H3C1.89,23 1,22.1 1,21V9H3V21M19,15H15V11H19V15Z" />
                            </svg>
                            Bookings
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('categories.index') }}">
                            <svg viewBox="0 0 24 24">
                                <path fill="#000000" d="M5.5,9A1.5,1.5 0 0,0 7,7.5A1.5,1.5 0 0,0 5.5,6A1.5,1.5 0 0,0 4,7.5A1.5,1.5 0 0,0 5.5,9M17.41,11.58C17.77,11.94 18,12.44 18,13C18,13.55 17.78,14.05 17.41,14.41L12.41,19.41C12.05,19.77 11.55,20 11,20C10.45,20 9.95,19.78 9.58,19.41L2.59,12.42C2.22,12.05 2,11.55 2,11V6C2,4.89 2.89,4 4,4H9C9.55,4 10.05,4.22 10.41,4.58L17.41,11.58M13.54,5.71L14.54,4.71L21.41,11.58C21.78,11.94 22,12.45 22,13C22,13.55 21.78,14.05 21.42,14.41L16.04,19.79L15.04,18.79L20.75,13L13.54,5.71Z" />
                            </svg>
                            Categories
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('objects.index') }}">
                            <svg viewBox="0 0 24 24">
                                <path fill="#000000" d="M22,17H18V10H22M23,8H17A1,1 0 0,0 16,9V19A1,1 0 0,0 17,20H23A1,1 0 0,0 24,19V9A1,1 0 0,0 23,8M4,6H22V4H4A2,2 0 0,0 2,6V17H0V20H14V17H4V6Z" />
                            </svg>
                            Objects
                        </a>
                    </li>
                    <li class="sidebar-link">
                        <a href="{{ route('users.index') }}">
                            <svg viewBox="0 0 24 24">
                                <path fill="#000000" d="M16,13C15.71,13 15.38,13 15.03,13.05C16.19,13.89 17,15 17,16.5V19H23V16.5C23,14.17 18.33,13 16,13M8,13C5.67,13 1,14.17 1,16.5V19H15V16.5C15,14.17 10.33,13 8,13M8,11A3,3 0 0,0 11,8A3,3 0 0,0 8,5A3,3 0 0,0 5,8A3,3 0 0,0 8,11M16,11A3,3 0 0,0 19,8A3,3 0 0,0 16,5A3,3 0 0,0 13,8A3,3 0 0,0 16,11Z" />
                            </svg>
                            Users
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
                                <svg viewBox="0 0 24 24">
                                    <path fill="#000000" d="M16,9C18.33,9 23,10.17 23,12.5V15H17V12.5C17,11 16.19,9.89 15.04,9.05L16,9M8,9C10.33,9 15,10.17 15,12.5V15H1V12.5C1,10.17 5.67,9 8,9M8,7A3,3 0 0,1 5,4A3,3 0 0,1 8,1A3,3 0 0,1 11,4A3,3 0 0,1 8,7M16,7A3,3 0 0,1 13,4A3,3 0 0,1 16,1A3,3 0 0,1 19,4A3,3 0 0,1 16,7M9,16.75V19H15V16.75L18.25,20L15,23.25V21H9V23.25L5.75,20L9,16.75Z" />
                                </svg>
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
                                <svg viewBox="0 0 24 24">
                                    <path fill="#000000" d="M16.56,5.44L15.11,6.89C16.84,7.94 18,9.83 18,12A6,6 0 0,1 12,18A6,6 0 0,1 6,12C6,9.83 7.16,7.94 8.88,6.88L7.44,5.44C5.36,6.88 4,9.28 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12C20,9.28 18.64,6.88 16.56,5.44M13,3H11V13H13" />
                                </svg>
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