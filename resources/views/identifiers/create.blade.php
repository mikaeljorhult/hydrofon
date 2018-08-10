@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create identifier'])
        @endcomponent

        <section>
            {!! Form::open(['route' => ['users.identifiers.store', $user]]) !!}
                @include('identifiers._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
