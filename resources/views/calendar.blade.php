@extends('layouts.app')

@section('content')
    @include('partials.resource-list')

    <main class="main-content">
        @include('partials/topbar')

        <section class="container">
            <header class="calendar-header">
                <h1>{{ $date->format('Y-m-d') }}</h1>
                <a href="{{ route('calendar', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}" title="Previous">
                    @svg('chevron-left')
                    <span class="screen-reader">Previous</span>
                </a>
                <a href="{{ route('calendar', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}" title="Next">
                    <span class="screen-reader">Next</span>
                    @svg('chevron-right')
                </a>
            </header>

            {!! Form::open(['route' => 'bookings.store']) !!}
                @include('partials.segel')

                @admin
                    <div class="input-group">
                        {!! Form::label('user_id', 'User') !!}
                        {!! Form::select('user_id', \Hydrofon\User::pluck('name', 'id'), auth()->user()->id) !!}
                    </div>
                @endadmin

                <div class="input-group">
                    {!! Form::label('resource_id', 'Resource') !!}
                    {!! Form::select('resource_id', \Hydrofon\Resource::pluck('name', 'id'), $resources->count() > 0 ? $resources->first()->id : null) !!}
                </div>

                <div class="input-group">
                    {!! Form::label('start_time', 'Time') !!}
                    {!! Form::text('start_time', null, ['placeholder' => 'Start']) !!}
                    {!! Form::label('end_time', '-') !!}
                    {!! Form::text('end_time', null, ['placeholder' => 'End']) !!}
                </div>

                <div class="form-group">
                    {!! Form::submit('Book', ['class' => 'btn btn-primary']) !!}
                </div>
            {!! Form::close() !!}
        </section>
    </main>
@endsection
