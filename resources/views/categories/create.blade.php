@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Create category</h1>

        <section>
            {!! Form::open(['route' => 'categories.store']) !!}
                @include('categories._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
