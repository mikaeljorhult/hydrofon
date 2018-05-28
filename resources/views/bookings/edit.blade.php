@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Edit booking</h1>

        <section>
            {!! Form::model($booking, ['route' => ['bookings.update', $booking->id], 'method' => 'PUT' ]) !!}
                @include('bookings._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
