@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create resource'" />

        <section>
            {!! Form::open(['route' => 'resources.store']) !!}
                @include('resources._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
