@extends('layouts.app')

@section('content')
    <section class="container">
        <header class="heading">
            <h1>Create booking</h1>
        </header>

        <section>
            {!! Form::open(['route' => 'bookings.store']) !!}
                @include('bookings._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
