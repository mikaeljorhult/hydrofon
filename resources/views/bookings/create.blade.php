@extends('layouts.app')

@section('content')
    <main class="main-content">
        <h1>Create booking</h1>

        <section>
            {!! Form::open(['route' => 'bookings.store']) !!}
                @include('bookings._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </main>
@endsection
