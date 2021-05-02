@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Edit booking'" />

        <section>
            {!! Form::model($booking, ['route' => ['bookings.update', $booking->id], 'method' => 'PUT' ]) !!}
                @include('bookings._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
