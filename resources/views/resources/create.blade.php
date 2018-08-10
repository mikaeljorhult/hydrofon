@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create resource'])
        @endcomponent

        <section>
            {!! Form::open(['route' => 'resources.store']) !!}
                @include('resources._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
