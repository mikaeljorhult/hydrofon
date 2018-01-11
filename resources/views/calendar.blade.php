@extends('layouts.app')

@section('content')
    @include('partials.object-list')

    <main class="main-content">
        @include('partials/topbar')

        <section class="container">
            <header class="calendar-header">
                <h1>{{ $date->format('Y-m-d') }}</h1>
                <a href="{{ route('calendar', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}">Previous</a>
                <a href="{{ route('calendar', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}">Next</a>
            </header>

            {!! Form::open(['route' => 'bookings.store']) !!}
                @include('partials.segel')

                @admin
                    <div class="form-group">
                        {!! Form::label('user_id', 'User') !!}
                        {!! Form::select('user_id', \Hydrofon\User::pluck('name', 'id'), auth()->user()->id) !!}
                    </div>
                @endadmin

                <div class="form-group">
                    {!! Form::label('object_id', 'Object') !!}
                    {!! Form::select('object_id', \Hydrofon\Object::pluck('name', 'id'), null) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('start_time', 'Time') !!}
                    {!! Form::text('start_time', null, ['placeholder' => 'Start']) !!}
                     - 
                    {!! Form::text('end_time', null, ['placeholder' => 'End']) !!}
                </div>
            {!! Form::close() !!}
        </section>
    </main>
@endsection
