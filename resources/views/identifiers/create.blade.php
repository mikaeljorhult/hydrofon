@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create identifier'])
        @endcomponent

        <section>
            {!! Form::open(['route' => [$identifiable->getTable().'.identifiers.store', $identifiable]]) !!}
                @include('identifiers._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
