@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Create user</h1>

        <section>
            {!! Form::open(['route' => 'users.store']) !!}
                @include('users._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
