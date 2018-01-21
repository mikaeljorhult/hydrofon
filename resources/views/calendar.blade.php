@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        @include('partials/topbar')

        <section class="container">
            <header class="calendar-header">
                <h1>{{ $date->format('Y-m-d') }}</h1>
                <a href="{{ route('calendar', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}" title="Previous">
                    @svg('chevron-left')
                </a>
                <a href="{{ route('calendar', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}" title="Next">
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
                    {!! Form::label('object_id', 'Object') !!}
                    {!! Form::select('object_id', \Hydrofon\Object::pluck('name', 'id'), $objects->count() > 0 ? $objects->first()->id : null) !!}
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
