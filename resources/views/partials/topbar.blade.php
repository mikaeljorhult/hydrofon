@admin
    <nav class="topbar">
        <section class="topbar-desk">
            {!! Form::open(['route' => 'desk']) !!}
                <div class="input-group">
                    {!! Form::search('search', $search ?? null, ['placeholder' => 'Search user...']) !!}
                    {!! Form::submit('Search', ['class' => 'btn btn-primary image-replacement']) !!}
                </div>
            {!! Form::close() !!}
        </section>

        <section class="topbar-impersonation">
            {!! Form::open(['route' => 'impersonation']) !!}
                <div class="input-group">
                    {!! Form::select('user_id', \Hydrofon\User::pluck('name', 'id'), session()->get('impersonate', null), ['placeholder' => 'Impersonate user...']) !!}
                    {!! Form::submit('Search', ['class' => 'btn btn-primary image-replacement']) !!}
                </div>
            {!! Form::close() !!}
        </section>
    </nav>
@endadmin