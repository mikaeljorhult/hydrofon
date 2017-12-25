@extends('layouts.app')

@section('content')
    <main class="main-content">
        <h1>Create object</h1>

        <section>
            {!! Form::open(['route' => 'objects.store']) !!}
                @include('objects._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </main>
@endsection
