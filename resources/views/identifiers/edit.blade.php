@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Edit identifier'])
        @endcomponent

        <section>
            {!! Form::model($identifier, ['route' => [$identifiable->getTable().'.identifiers.update', $identifiable, $identifier], 'method' => 'PUT' ]) !!}
                @include('identifiers._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
