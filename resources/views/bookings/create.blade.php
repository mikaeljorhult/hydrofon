@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Create booking</h1>

        <section>
            {!! Form::open(['route' => 'bookings.store']) !!}
                @include('bookings._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
