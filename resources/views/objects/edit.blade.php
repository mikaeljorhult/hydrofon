@extends('layouts.app')

@section('content')
    <main class="main-content">
        <h1>Edit object</h1>

        <section>
            {!! Form::model($object, ['route' => ['objects.update', $object->id], 'method' => 'PUT' ]) !!}
                @include('objects._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </main>
@endsection
