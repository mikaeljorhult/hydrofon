@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Edit booking'])
        @endcomponent

        <section>
            {!! Form::model($booking, ['route' => ['bookings.update', $booking->id], 'method' => 'PUT' ]) !!}
                @include('bookings._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
