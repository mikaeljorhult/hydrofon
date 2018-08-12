@extends('layouts.app')

@section('content')
    <section class="container">
        @component('components.heading', ['title' => 'Create bucket'])
        @endcomponent

        <section>
            {!! Form::open(['route' => 'buckets.store']) !!}
                @include('buckets._form', ['submitButtonText' => 'Create'])
            {!! Form::close() !!}
        </section>
    </section>
@endsection
