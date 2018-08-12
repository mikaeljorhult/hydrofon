@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create group'])
        @endcomponent

        <section>
            {!! Form::open(['route' => 'groups.store']) !!}
                @include('groups._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
