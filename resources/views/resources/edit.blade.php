@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Edit resource'])
        @endcomponent

        <section>
            {!! Form::model($resource, ['route' => ['resources.update', $resource->id], 'method' => 'PUT' ]) !!}
                @include('resources._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
