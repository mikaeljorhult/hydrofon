@admin
    <nav class="topbar">
        <section class="topbar-desk">
            {!! Form::open(['route' => 'desk']) !!}
                <div class="input-group">
                    <label for="search">
                        @svg('magnify')
                    </label>
                    {!! Form::search('search', $search ?? null, ['placeholder' => 'Search user...']) !!}
                    {!! Form::submit('Search', ['class' => 'btn btn-primary screen-reader']) !!}
                </div>
            {!! Form::close() !!}
        </section>

        <section class="topbar-impersonation">
            {!! Form::open(['route' => 'impersonation']) !!}
                <div class="input-group">
                    <label for="user_id">
                        @svg('account-switch')
                    </label>
                    {!! Form::select('user_id', \Hydrofon\User::pluck('name', 'id'), session()->get('impersonate', null), ['placeholder' => 'Impersonate user...']) !!}
                    {!! Form::submit('Impersonate', ['class' => 'btn btn-primary screen-reader']) !!}
                </div>
            {!! Form::close() !!}
        </section>
    </nav>
@endadmin