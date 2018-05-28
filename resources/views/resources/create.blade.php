@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Create resource</h1>

        <section>
            {!! Form::open(['route' => 'resources.store']) !!}
                @include('resources._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
