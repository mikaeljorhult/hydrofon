@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create user'])
        @endcomponent

        <section>
            {!! Form::open(['route' => 'users.store']) !!}
                @include('users._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
