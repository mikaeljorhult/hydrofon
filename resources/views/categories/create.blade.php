@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create category'" />

        <section>
            {!! Form::open(['route' => 'categories.store']) !!}
                @include('categories._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
