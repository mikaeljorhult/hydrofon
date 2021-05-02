@extends('layouts.app')

@section('content')
    <section class="container">
        <x-heading :title="'Create identifier'" />

        <section>
            {!! Form::open(['route' => [$identifiable->getTable().'.identifiers.store', $identifiable]]) !!}
                @include('identifiers._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
