@admin
    <nav class="topbar">
        <section class="topbar-desk">
            {!! Form::open(['route' => 'desk']) !!}
                {!! Form::search('search', $search ?? null, ['placeholder' => 'Search user...']) !!}
                {!! Form::submit('Search', ['class' => 'screen-reader']) !!}
            {!! Form::close() !!}
        </section>
    </nav>
@endadmin