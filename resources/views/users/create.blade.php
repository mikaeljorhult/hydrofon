@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create user'" />

        <section>
            {!! Form::open(['route' => 'users.store']) !!}
                @include('users._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
