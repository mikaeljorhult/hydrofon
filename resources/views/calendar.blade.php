@extends('layouts.app')

@section('title', 'Calendar')

@push('initial-json')
    window.HYDROFON.date = @json((int) $date->startOfDay()->format('U'));
    window.HYDROFON.selectedResources = @json($resources->pluck('id'));
@endpush

@section('sidebar')
    @include('partials.resource-list')
@endsection

@section('content')
    <section class="container">
        <calendar-header
            class="calendar-header"
            v-bind:date="date"
        >
            <h1>{{ $date->format('Y-m-d') }}</h1>
            <a href="{{ route('calendar', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}" title="Previous">
                @svg('cheveron-left', 'w-6')
                <span class="screen-reader">Previous</span>
            </a>
            <a href="{{ route('calendar', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}" title="Next">
                <span class="screen-reader">Next</span>
                @svg('cheveron-right', 'w-6')
            </a>
        </calendar-header>

        {!! Form::open(['route' => 'bookings.store']) !!}
            @include('partials.segel')

            <section>
                <h2>Create booking</h2>

                @admin
                    <div class="mb-4">
                        {!! Form::label('user_id', 'User', ['class' => 'label']) !!}
                        {!! Form::select('user_id', \Hydrofon\User::orderBy('name')->pluck('name', 'id'), auth()->user()->id, ['class' => 'field' . ($errors->has('user_id') ? ' is-invalid' : '')]) !!}
                    </div>
                @endadmin

                <div class="mb-4">
                    {!! Form::label('resource_id', 'Resource', ['class' => 'label']) !!}
                    {!! Form::select('resource_id', \Hydrofon\Resource::orderBy('name')->pluck('name', 'id'), $resources->count() > 0 ? $resources->first()->id : null, ['class' => 'field' . ($errors->has('resource_id') ? ' is-invalid' : '')]) !!}
                </div>

                <div class="flex flex-wrap mb-6">
                    <div class="w-full mb-4 sm:w-1/2 sm:pr-2 sm:m-0">
                        {!! Form::label('start_time', 'Start time', ['class' => 'label']) !!}
                        {!! Form::text('start_time', $date->format('Y-m-d ') . now()->addHours(1)->format('H:00'), ['placeholder' => 'Start', 'class' => 'field' . ($errors->has('start_time') ? ' is-invalid' : '')]) !!}
                    </div>

                    <div class="w-full sm:w-1/2 sm:pl-2">
                        {!! Form::label('end_time', 'End time', ['class' => 'label']) !!}
                        {!! Form::text('end_time', $date->format('Y-m-d ') . now()->addHours(3)->format('H:00'), ['placeholder' => 'End', 'class' => 'field' . ($errors->has('end_time') ? ' is-invalid' : '')]) !!}
                    </div>
                </div>

                <div class="mt-6">
                    {!! Form::submit('Book', ['class' => 'btn btn-primary']) !!}
                </div>
            </section>
        {!! Form::close() !!}
    </section>
@endsection
