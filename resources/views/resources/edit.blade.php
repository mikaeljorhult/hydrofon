@extends('layouts.app')

@section('content')
    <section class="container">
        <h1>Edit resource</h1>

        <section>
            {!! Form::model($resource, ['route' => ['resources.update', $resource->id], 'method' => 'PUT' ]) !!}
                @include('resources._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
