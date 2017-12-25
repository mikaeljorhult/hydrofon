<aside class="sidebar">
    <header class="branding">
        <h1 class="screen-reader">
            <a href="{{ url('/') }}">{{ config('app.name', 'Hydrofon') }}</a>
        </h1>

        <a href="{{ url('/') }}">
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
            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(auth()->user()->email)) }}?d=mm"
                 class="user-avatar"
                 alt="{{ auth()->user()->name }}"/>
            <div class="user-name">{{ auth()->user()->name }}</div>
        </section>

        <nav class="main-navigation">
            <ul>
                <li><a href="{{ route('categories.index') }}">Categories</a></li>
                <li><a href="{{ route('objects.index') }}">Objects</a></li>
                <li><a href="{{ route('users.index') }}">Users</a></li>
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