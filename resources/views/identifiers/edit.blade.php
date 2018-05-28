@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Edit identifier</h1>

        <section>
            {!! Form::model($identifier, ['route' => ['users.identifiers.update', $user, $identifier], 'method' => 'PUT' ]) !!}
                @include('identifiers._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
