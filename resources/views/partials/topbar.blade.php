@admin
    <nav class="topbar">
        <section class="topbar-desk">
            {!! Form::open(['route' => 'desk']) !!}
                <label for="search">
                    @svg('magnify')
                    {!! Form::search('search', $search ?? null, ['placeholder' => 'Search user...']) !!}
                </label>

                {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        </section>

        <section class="topbar-impersonation">
            {!! Form::open(['route' => 'impersonation']) !!}
                <label for="user_id">
                    @svg('account-switch')
                    {!! Form::select('user_id', \Hydrofon\User::pluck('name', 'id'), session()->get('impersonate', null), ['placeholder' => 'Impersonate user...']) !!}
                </label>

                {!! Form::submit('Impersonate', ['class' => 'btn btn-primary screen-reader']) !!}
            {!! Form::close() !!}
        </section>
    </nav>
@endadmin