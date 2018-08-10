@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create category'])
        @endcomponent

        <section>
            {!! Form::open(['route' => 'categories.store']) !!}
                @include('categories._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
