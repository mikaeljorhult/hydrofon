@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create booking'" />

        <section>
            {!! Form::open(['route' => 'bookings.store']) !!}
                @include('bookings._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
