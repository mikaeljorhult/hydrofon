<aside class="sidebar">
    <header class="branding">
        <h1 class="screen-reader">{{ config('app.name', 'Hydrofon') }}</h1>

        <a href="{{ route('home') }}">
            <img src="{{ asset('img/logo.png') }}" width="281" alt="{{ config('app.name', 'Hydrofon') }}"/>
        </a>
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
            <img src="https://www.gravatar.com/avatar/" class="user-avatar" alt="{{ auth()->user()->name }}" />
            <div class="user-name">{{ auth()->user()->name }}</div>
        </section>

        <nav class="main-navigation">
            <ul>
                <li>Section A</li>
                <li>Section B</li>
                <li>Section C</li>
                <li>Section D</li>
            </ul>
        </nav>

        <section>
            <ul>
                <li>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        Log out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </section>
    @endauth
</aside>