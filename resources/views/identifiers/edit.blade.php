@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Edit identifier'" />

        <section>
            {!! Form::model($identifier, ['route' => [$identifiable->getTable().'.identifiers.update', $identifiable, $identifier], 'method' => 'PUT' ]) !!}
                @include('identifiers._form', ['submitButtonText' => 'Update'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
