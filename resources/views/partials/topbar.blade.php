@admin
    <nav class="topbar">
        <section class="topbar-desk">
            {!! Form::open(['route' => 'desk']) !!}
                @svg('magnify')

                <label for="search" class="screen-reader">
                    Search for user
                </label>

                {!! Form::text('search', $search ?? null, ['placeholder' => 'Search user...']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        </section>

        <section class="topbar-impersonation">
            {!! Form::open(['route' => 'impersonation']) !!}
                @svg('account-switch')

                <label for="user_id" class="screen-reader">
                    Select user to impersonate
                </label>

                {!! Form::select('user_id', \Hydrofon\User::pluck('name', 'id'), session()->get('impersonate', null), ['placeholder' => 'Impersonate user...']) !!}
                {!! Form::submit('Impersonate', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        </section>
    </nav>
@endadmin