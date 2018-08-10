@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create booking'])
        @endcomponent

        <section>
            {!! Form::open(['route' => 'bookings.store']) !!}
                @include('bookings._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
